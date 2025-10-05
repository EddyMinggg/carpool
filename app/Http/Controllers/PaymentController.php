<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripJoin;
use Illuminate\Http\Request;

use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::findOrFail($id);

        // 如果付款已經確認，跳轉到行程詳情頁面
        if ($payment->paid) {
            return redirect()->route('trips.show', ['id' => $payment->trip_id])
                ->with('success', __('Payment confirmed! You have successfully joined the trip.'));
        }

        return view('payment.code', ['payment' => $payment]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        // 檢查是否為多人預訂
        $isGroupBooking = $request->has('is_group_booking') && $request->input('is_group_booking') == '1';
        
        if ($isGroupBooking) {
            // 多人預訂驗證
            $request->validate([
                'trip_id' => 'required|exists:trips,id',
                'people_count' => 'required|integer|min:1|max:5',
                'passengers' => 'required|array',
                'passengers.*.name' => 'required|string|max:100',
                'passengers.*.phone' => 'required|string|max:20',
                'passengers.*.phone_country_code' => 'required|in:+852,+86',
                'passengers.*.pickup_location' => 'required|string|max:255',
                'total_amount' => 'required|numeric|min:0',
            ]);
        } else {
            // 單人預訂驗證
            $request->validate([
                'trip_id' => 'required|exists:trips,id',
                'amount' => 'required|decimal:0,2',
                'pickup_location' => 'required|string|max:255',
            ]);
        }

        $trip = Trip::find($request->input('trip_id'));
        
        if ($isGroupBooking) {
            return $this->handleGroupBooking($request, $trip);
        } else {
            return $this->handleSingleBooking($request, $trip, $user);
        }
    }
    
    /**
     * 處理多人預訂
     */
    private function handleGroupBooking(Request $request, Trip $trip)
    {
        $passengers = $request->input('passengers');
        $peopleCount = count($passengers);
        
        // 檢查行程是否有足夠空位
        $currentPeople = $trip->joins()->count();
        if (($currentPeople + $peopleCount) > $trip->max_people) {
            return redirect()->back()->with('error', __('Not enough spaces available for this group booking.'));
        }
        
        // 檢查行程狀態
        if ($trip->trip_status !== 'awaiting' || $trip->planned_departure_time <= Carbon::now()->addHours(9)) {
            return redirect()->back()->with('error', __('This trip is no longer available for joining.'));
        }
        
        // 檢查是否有重複的手機號碼
        $phoneNumbers = [];
        foreach ($passengers as $passenger) {
            $fullPhone = $passenger['phone_country_code'] . $passenger['phone'];
            if (in_array($fullPhone, $phoneNumbers)) {
                return redirect()->back()->with('error', __('Duplicate phone numbers are not allowed in group booking.'));
            }
            $phoneNumbers[] = $fullPhone;
            
            // 檢查此手機號碼是否已加入行程
            $existingJoin = $trip->joins()->where('user_phone', $fullPhone)->first();
            if ($existingJoin) {
                return redirect()->back()->with('error', __('Phone number :phone has already joined this trip.', ['phone' => $fullPhone]));
            }
        }
        
        // 計算定價（新邏輯：全額付款）
        $pricePerPerson = $request->input('price_per_person', $this->calculatePriceForTrip($trip, $peopleCount));
        $totalAmount = $pricePerPerson * $peopleCount;
        
        // 創建主要付款記錄（代表整個團體）
        $mainPayment = Payment::create([
            'reference_code' => strtoupper(bin2hex(random_bytes(5))),
            'trip_id' => $trip->id,
            'user_phone' => $passengers[0]['phone_country_code'] . $passengers[0]['phone'], // 主預訂人
            'amount' => $totalAmount,
            'pickup_location' => $passengers[0]['pickup_location'],
            'type' => 'group_full_payment',
            'group_size' => $peopleCount,
        ]);
        
        // 為每個乘客創建 TripJoin 記錄
        foreach ($passengers as $index => $passenger) {
            $fullPhone = $passenger['phone_country_code'] . $passenger['phone'];
            
            $trip->joins()->create([
                'user_phone' => $fullPhone,
                'user_fee' => $pricePerPerson,
                'pickup_location' => $passenger['pickup_location'],
                'payment_confirmation' => false,
                // 可以添加額外字段來標記這是團體預訂的一部分
            ]);
            
            // 為每個乘客創建個別的付款記錄（方便追蹤）
            if ($index > 0) { // 第一個已經是主要付款記錄
                Payment::create([
                    'reference_code' => $mainPayment->reference_code . '-' . ($index + 1),
                    'trip_id' => $trip->id,
                    'user_phone' => $fullPhone,
                    'amount' => $pricePerPerson,
                    'pickup_location' => $passenger['pickup_location'],
                    'type' => 'group_member_payment',
                    'parent_payment_id' => $mainPayment->id,
                ]);
            }
        }
        
        return redirect()->route('payment.code', ['id' => $mainPayment->id])
            ->with('success', __('Group booking created successfully for :count people!', ['count' => $peopleCount]));
    }
    
    /**
     * 處理單人預訂
     */
    private function handleSingleBooking(Request $request, Trip $trip, $user)
    {
        // 檢查用戶是否已經加入
        $existingJoin = $trip->joins()->where('user_phone', $user->phone ?? $request->user_phone)->first();
        if ($existingJoin) {
            return redirect()->back()->with('error', __('You have already joined this trip.'));
        }

        // 檢查行程是否已滿
        if ($trip->joins()->count() >= $trip->max_people) {
            return redirect()->back()->with('error', __('This trip is full.'));
        }

        // 檢查行程狀態
        if ($trip->trip_status !== 'awaiting' || $trip->planned_departure_time <= Carbon::now()->addHours(9)) {
            return redirect()->back()->with('error', __('This trip is no longer available for joining.'));
        }

        // 計算價格
        $currentPeople = $trip->joins()->count();
        $newPeopleCount = $currentPeople + 1;
        $pricePerPerson = $this->calculatePriceForTrip($trip, $newPeopleCount);

        $payment = Payment::create([
            'reference_code' => strtoupper(bin2hex(random_bytes(5))),
            'trip_id' => $request->input('trip_id'),
            'user_phone' => $user->phone ?? $request->user_phone,
            'amount' => $pricePerPerson,
            'pickup_location' => $request->input('pickup_location'),
            'type' => 'full_payment',
        ]);

        // 創建加入記錄
        $trip->joins()->create([
            'user_phone' => $user->phone ?? $request->user_phone,
            'user_fee' => $pricePerPerson,
            'pickup_location' => $request->input('pickup_location'),
        ]);

        // 更新所有現有成員的費用
        TripJoin::where('trip_id', $trip->id)
            ->update([
                'user_fee' => $pricePerPerson,
            ]);

        return redirect()->route('payment.code', ['id' => $payment->id]);
    }
    
    /**
     * 計算行程價格（新定價邏輯）
     */
    private function calculatePriceForTrip(Trip $trip, int $peopleCount): float
    {
        if ($trip->type === 'golden') {
            // 黃金時段：固定每人 250
            return 250.00;
        } else if ($trip->type === 'fixed') {
            // 固定價格類型
            return (float) $trip->price_per_person;
        } else {
            // 普通時段新定價邏輯
            if ($peopleCount >= 4) {
                // 4人以上：每人 225（275 - 50 折扣）
                return 225.00;
            } else {
                // 1-3人：每人 275
                return 275.00;
            }
        }
    }
}

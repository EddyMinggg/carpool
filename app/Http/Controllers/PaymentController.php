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

        // 檢查是否為 group booking
        $isGroupBooking = $payment->group_size && $payment->group_size > 1;
        
        // 獲取相關的TripJoin記錄（僅用於備用，主要使用payment中的group_size）
        $groupTripJoins = [];
        if ($isGroupBooking) {
            // 簡化查詢，只取最近的記錄作為備用
            $groupTripJoins = TripJoin::where('trip_id', $payment->trip_id)
                ->orderBy('created_at', 'desc')
                ->limit($payment->group_size ?? 2)
                ->get();
        }

        // 為單人預訂也獲取 TripJoin 數據（用於顯示 pickup_location）
        $userTripJoin = null;
        if (!$isGroupBooking) {
            $userTripJoin = TripJoin::where('trip_id', $payment->trip_id)
                ->where('user_phone', $payment->user_phone)
                ->first();
        }

        return view('payment.code', [
            'payment' => $payment,
            'isGroupBooking' => $isGroupBooking,
            'groupTripJoins' => $groupTripJoins,
            'userTripJoin' => $userTripJoin
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        // 檢查是否為多人預訂 - 優先使用 people_count，其次檢查 passengers 數組長度
        $peopleCount = $request->input('people_count');
        $passengers = $request->input('passengers', []);
        
        // 如果有 people_count，則以此為準；否則使用 passengers 數組長度
        if ($peopleCount !== null) {
            $isGroupBooking = (int)$peopleCount > 1;
        } else {
            $isGroupBooking = count($passengers) > 1;
        }
        
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
                'coupon_code' => 'nullable|string|max:50',
                'coupon_discount' => 'nullable|numeric|min:0',
                'subtotal_amount' => 'nullable|numeric|min:0',
            ]);
        } else {
            // 單人預訂驗證 - 使用和多人預訂相同的結構
            $request->validate([
                'trip_id' => 'required|exists:trips,id',
                'people_count' => 'required|integer|min:1|max:1',
                'passengers' => 'required|array',
                'passengers.*.name' => 'required|string|max:100',
                'passengers.*.phone' => 'required|string|max:20',
                'passengers.*.phone_country_code' => 'required|in:+852,+86',
                'passengers.*.pickup_location' => 'required|string|max:255',
                'total_amount' => 'required|numeric|min:0',
                'coupon_code' => 'nullable|string|max:50',
                'coupon_discount' => 'nullable|numeric|min:0',
                'subtotal_amount' => 'nullable|numeric|min:0',
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
        
        // 檢查行程是否有足夠空位（使用新的方法，考慮30分鐘超時）
        $availableSlots = $trip->getAvailableSlots();
        if ($peopleCount > $availableSlots) {
            return redirect()->back()->with('error', __('Not enough spaces available for this group booking. Only :slots slot(s) available.', ['slots' => $availableSlots]));
        }
        
        // 檢查行程狀態和預訂截止時間
        $now = Carbon::now('Asia/Hong_Kong');
        $departureTime = Carbon::parse($trip->planned_departure_time, 'Asia/Hong_Kong');
        
        // 根據 trip 類型計算預訂截止時間
        if ($trip->type === 'golden') {
            // Golden hour: departure time 前 1 小時截止預訂
            $bookingDeadline = $departureTime->copy()->subHour();
        } else {
            // Normal trip: departure time 前 48 小時截止預訂
            $bookingDeadline = $departureTime->copy()->subHours(48);
        }
        
        if ($trip->trip_status !== 'awaiting' || $now->gte($bookingDeadline)) {
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
            
            // 檢查此手機號碼是否已加入行程（使用新的邏輯）
            if (!TripJoin::canUserRebook($fullPhone, $trip->id)) {
                return redirect()->back()->with('error', __('Phone number :phone has already joined this trip or has a pending booking.', ['phone' => $fullPhone]));
            }
        }
        
        // 計算定價（包含優惠券折扣）
        $pricePerPerson = $request->input('price_per_person', $this->calculatePriceForTrip($trip, $peopleCount));
        $subtotalAmount = $request->input('subtotal_amount', $pricePerPerson * $peopleCount);
        $totalAmount = $request->input('total_amount', $subtotalAmount);
        $couponDiscount = $request->input('coupon_discount', 0);
        
        // 處理優惠券（如果有）
        $couponCode = $request->input('coupon_code');
        if ($couponCode && $couponDiscount > 0) {
            // 驗證並更新優惠券使用次數
            $coupon = \App\Models\Coupon::where('code', strtoupper($couponCode))
                ->where('enabled', true)
                ->first();
            
            if ($coupon) {
                $coupon->increment('used_count');
            }
        }
        
        // 創建主要付款記錄（代表整個團體）
        $mainPayment = Payment::create([
            'reference_code' => strtoupper(bin2hex(random_bytes(5))),
            'trip_id' => $trip->id,
            'user_phone' => $passengers[0]['phone_country_code'] . $passengers[0]['phone'], // 主預訂人
            'amount' => $totalAmount,
            'type' => 'group',
            'group_size' => $peopleCount,
            'coupon_code' => $couponCode,
            'coupon_discount' => $couponDiscount,
        ]);
        
        // 為每個乘客創建 TripJoin 記錄
        foreach ($passengers as $index => $passenger) {
            $fullPhone = $passenger['phone_country_code'] . $passenger['phone'];
            
            $trip->joins()->create([
                'user_phone' => $fullPhone,
                'user_fee' => $pricePerPerson,
                'pickup_location' => $passenger['pickup_location'],
                'payment_confirmation' => false,
            ]);
        }

        // NOTE: WhatsApp 通知已移到 Admin 確認付款後才發送
        // 不在此處發送通知，因為團體成員還未確認付款 (payment_confirmation = false)
        
        return redirect()->route('payment.code', ['id' => $mainPayment->id])
            ->with('success', __('Group booking created successfully for :count people!', ['count' => $peopleCount]));
    }
    
    /**
     * 處理單人預訂
     */
    private function handleSingleBooking(Request $request, Trip $trip, $user)
    {
        $passengers = $request->input('passengers');
        $passenger = $passengers[0]; // 單人預訂只有一個乘客
        $userPhone = $passenger['phone_country_code'] . $passenger['phone'];
        
        // 檢查用戶是否可以預訂（使用新的邏輯）
        if (!TripJoin::canUserRebook($userPhone, $trip->id)) {
            return redirect()->back()->with('error', __('You have already joined this trip or have a pending booking. Please complete your payment or wait for the booking to expire (30 minutes).'));
        }

        // 檢查行程是否已滿（使用新的方法）
        $availableSlots = $trip->getAvailableSlots();
        if ($availableSlots <= 0) {
            return redirect()->back()->with('error', __('This trip is full.'));
        }

        // 檢查行程狀態和預訂截止時間
        $now = Carbon::now('Asia/Hong_Kong');
        $departureTime = Carbon::parse($trip->planned_departure_time, 'Asia/Hong_Kong');
        
        // 根據 trip 類型計算預訂截止時間
        if ($trip->type === 'golden') {
            // Golden hour: departure time 前 1 小時截止預訂
            $bookingDeadline = $departureTime->copy()->subHour();
        } else {
            // Normal trip: departure time 前 48 小時截止預訂
            $bookingDeadline = $departureTime->copy()->subHours(48);
        }
        
        if ($trip->trip_status !== 'awaiting' || $now->gte($bookingDeadline)) {
            return redirect()->back()->with('error', __('This trip is no longer available for joining.'));
        }

        // 計算價格並處理優惠券
        $totalAmount = $request->input('total_amount');
        $couponDiscount = $request->input('coupon_discount', 0);
        $couponCode = $request->input('coupon_code');
        
        // 處理優惠券（如果有）
        if ($couponCode && $couponDiscount > 0) {
            $coupon = \App\Models\Coupon::where('code', strtoupper($couponCode))
                ->where('enabled', true)
                ->first();
            
            if ($coupon) {
                $coupon->increment('used_count');
            }
        }

        $payment = Payment::create([
            'reference_code' => strtoupper(bin2hex(random_bytes(5))),
            'trip_id' => $request->input('trip_id'),
            'user_phone' => $userPhone,
            'amount' => $totalAmount,
            'type' => 'individual',
            'group_size' => 1,
            'coupon_code' => $couponCode,
            'coupon_discount' => $couponDiscount,
        ]);

        // 獲取當前隊伍成員（在創建新成員之前）
        $existingMembers = $trip->joins()->with('user')->get()->filter(function($join) {
            return $join->user !== null; // 只包含有用戶記錄的成員
        })->pluck('user')->toArray();

        // 創建加入記錄
        $trip->joins()->create([
            'user_phone' => $userPhone,
            'user_fee' => $totalAmount,
            'pickup_location' => $passenger['pickup_location'],
        ]);

        // 重新計算所有成員的費用
        $currentPeople = $trip->joins()->count();
        $newPricePerPerson = $this->calculatePriceForTrip($trip, $currentPeople);
        TripJoin::where('trip_id', $trip->id)
            ->update([
                'user_fee' => $newPricePerPerson,
            ]);

        // NOTE: WhatsApp 通知已移到 Admin 確認付款後才發送
        // 不在此處發送通知，因為用戶還未確認付款 (payment_confirmation = false)

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

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
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'amount' => 'required|decimal:0,2',
            'pickup_location' => 'required|string|max:255',
        ]);

        $trip = Trip::find($request->input('trip_id'));

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


        // 使用新的定價服務計算費用
        $pricingService = app(\App\Services\PricingService::class);
        $currentPeople = $trip->joins()->count();
        $newPeopleCount = $currentPeople + 1;
        
        // 計算新的每人費用
        $pricePerPerson = $pricingService->calculatePricePerPerson($trip, $newPeopleCount);

        $payment = Payment::create([
            'reference_code' => strtoupper(bin2hex(random_bytes(5))),
            'trip_id' => $request->input('trip_id'),
            'user_phone' => $user->phone ?? $request->user_phone,
            'amount' => $pricePerPerson, // 使用計算出的價格
            'pickup_location' => $request->input('pickup_location'),
            'type' => 'full_payment', // 新邏輯：全款支付
        ]);

        /**  ==================== Join Trip ==================== **/

        // 創建加入記錄
        $trip->joins()->create([
            'user_phone' => $user->phone ?? $request->user_phone,
            'user_fee' => $pricePerPerson,
            'pickup_location' => $request->input('pickup_location'),
        ]);

        // 更新所有現有成員的費用（包括新加入的用戶）
        // 在新邏輯下，每人費用根據最終人數計算
        TripJoin::where('trip_id', $trip->id)
            ->update([
                'user_fee' => $pricePerPerson,
                'pickup_location' => $request->input('pickup_location')
            ]);


        return redirect()->route('payment.code', ['id' => $payment->id]);
    }
}

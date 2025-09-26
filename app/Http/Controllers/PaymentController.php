<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripJoin;
use Illuminate\Http\Request;

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
        $existingJoin = $trip->joins()->where('user_id', $user->id)->first();
        if ($existingJoin) {
            return redirect()->back()->with('error', __('You have already joined this trip.'));
        }

        // 檢查行程是否已滿
        if ($trip->joins()->count() >= $trip->max_people) {
            return redirect()->back()->with('error', __('This trip is full.'));
        }

        // 檢查行程狀態
        if ($trip->trip_status !== 'awaiting') {
            return redirect()->back()->with('error', __('This trip is no longer available for joining.'));
        }

        $payment = Payment::create([
            'reference_code' => strtoupper(bin2hex(random_bytes(5))),
            'trip_id' => $request->input('trip_id'),
            'user_id' => $user->id,
            'amount' => $request->input('amount'),
            'pickup_location' => $request->input('pickup_location'),
        ]);

        /**  ==================== Join Trip ==================== **/

        // 計算用戶費用（基於當前人數動態計算）
        $currentPeople = $trip->joins()->count();
        $totalCost = $trip->base_price; // 從數據庫獲取基礎費用
        $newUserFee = $totalCost / ($currentPeople + 1); // +1 因為包括即將加入的用戶

        // 創建加入記錄
        $trip->joins()->create([
            'user_id' => $user->id,
            'pickup_location' => $request->input('pickup_location'),
            'join_role' => 'normal',
            'user_fee' => round($newUserFee, 2),
            'vote_info' => json_encode([])
        ]);

        // 更新所有現有成員的費用（包括新加入的用戶）
        $newPeopleCount = $currentPeople + 1;
        $updatedUserFee = $totalCost / $newPeopleCount;

        TripJoin::where('trip_id', $trip->id)
            ->update(['user_fee' => round($updatedUserFee, 2)]);


        return redirect()->route('payment.code', ['id' => $payment->id]);
    }
}

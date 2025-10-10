<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TripJoin;
use Illuminate\Http\Request;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['trip', 'tripJoins'])
            ->where('user_phone', Auth::user()->phone)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('trips.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $currentUser = Auth::user();

        $userPhone = $currentUser->phone ?? $request->user_phone;

        $payment = Payment::where('trip_id', $id)->where('user_phone', $userPhone)->first();

        $hasLeft = !(TripJoin::where('trip_id', $id)->where('user_phone', $userPhone)->exists()) && $payment != null;

        $madeDepositPayment = null;
        if ($payment) {
            $madeDepositPayment = $payment->paid;
        }
        // 只有當付款未完成且用戶未離開且行程仍在進行中時才跳轉到付款頁面
        if ($madeDepositPayment !== null && !$madeDepositPayment && !$hasLeft) {
            $trip = Trip::findOrFail($id);
            // 檢查行程是否已過期或已完成
            $now = Carbon::now();
            $isExpired = $trip->planned_departure_time < $now;
            $isCompleted = in_array($trip->trip_status, ['departed', 'completed']);

            // 只有未過期且未完成的行程才跳轉到付款頁面
            if (!$isExpired && !$isCompleted) {
                return redirect()->route('payment.code', ['id' => $payment->id]);
            }
        }

        $trip = Trip::with(['joins.user', 'creator'])->findOrFail($id);

        // 計算當前用戶是否已加入且payment已確認
        $userJoin = $trip->joins->where('user_phone', $userPhone)->first();
        $hasJoined = $userJoin !== null && $userJoin->payment_confirmed;
        
        // 如果有payment記錄且已付款，但TripJoin記錄未確認，說明管理員還未處理
        $hasPaidButNotConfirmed = $payment && $payment->paid && $userJoin && !$userJoin->payment_confirmed;

        // 計算價格（使用雙層定價系統）
        $currentPeople = $trip->joins->count();

        // 使用新的定價系統計算每人費用
        if ($trip->type === 'fixed') {
            // 固定價格類型：顯示每人價格
            $price = $trip->price_per_person;
        } else {
            // Golden 或 Normal 類型：根據人數和類型計算價格
            $peopleCount = max(1, $currentPeople); // 至少1人

            if ($trip->type === 'golden') {
                // 黃金時段：固定每人250，最少1人
                $price = $trip->price_per_person; // 250
            } else {
                // 普通時段：每人275，4人有折扣
                if ($peopleCount >= 4 && $trip->four_person_discount > 0) {
                    $price = $trip->price_per_person - $trip->four_person_discount;
                } else {
                    $price = $trip->price_per_person; // 275
                }
            }
        }

        // 格式化時間
        $departureTime = Carbon::parse($trip->planned_departure_time);

        // 計算可用槽位數
        $availableSlots = max(0, $trip->max_people - $currentPeople);

        // 獲取分配的司機信息
        $assignedDriver = null;
        if ($trip->tripDriver && in_array($trip->tripDriver->status, ['assigned', 'confirmed'])) {
            $assignedDriver = $trip->getDriver();
        }

        return view('trips.show', compact(
            'trip',
            'userPhone',
            'hasJoined',
            'hasLeft',
            'hasPaidButNotConfirmed',
            'currentPeople',
            'availableSlots',
            'price',
            'departureTime',
            'assignedDriver'
        ))->with('userHasJoined', $hasJoined);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Dashboard: 顯示分組的拼車行程（資料處理在 Controller）
     */
    public function dashboard(Request $request)
    {
        // 確保使用香港時間
        $now = Carbon::now('Asia/Hong_Kong');

        // 只顯示未來2週內且未過 departure time 的行程
        $trips = Trip::with('joins')
            ->where('planned_departure_time', '>', $now)
            ->where('planned_departure_time', '<=', $now->copy()->addWeeks(2))
            ->whereNot('trip_status', 'cancelled')
            ->get();

        $groupedTrips = $trips->filter(function ($trip) use ($now) {
            if (empty($trip->planned_departure_time)) {
                return false;
            }

            $departureTime = $trip->planned_departure_time instanceof Carbon
                ? $trip->planned_departure_time->setTimezone('Asia/Hong_Kong')
                : Carbon::parse($trip->planned_departure_time, 'Asia/Hong_Kong');

            // 只顯示未過 departure time 的 trip
            return $departureTime->gt($now);
        })->map(function ($trip) use ($now) {
            $dt = $trip->planned_departure_time instanceof Carbon
                ? $trip->planned_departure_time->setTimezone('Asia/Hong_Kong')
                : Carbon::parse($trip->planned_departure_time, 'Asia/Hong_Kong');
            $trip->date = $dt->format('Y-m-d');
            $trip->formatted_departure_time = $dt->format('H:i');
            $trip->current_people = isset($trip->joins) ? $trip->joins->count() : 0;

            // 使用新的定價系統計算每人費用
            if ($trip->type === 'fixed') {
                // 固定價格類型：顯示每人價格
                $trip->price = $trip->price_per_person;
            } else {
                // Golden 或 Normal 類型：根據人數計算價格
                $currentPeople = max(1, $trip->current_people); // 至少1人

                if ($trip->type === 'golden') {
                    // 黃金時段：固定每人250，最少1人
                    $trip->price = $trip->price_per_person; // 250
                } else {
                    // 普通時段：每人275，4人有折扣
                    if ($currentPeople >= 4 && $trip->four_person_discount > 0) {
                        $trip->price = $trip->price_per_person - $trip->four_person_discount;
                    } else {
                        $trip->price = $trip->price_per_person; // 275
                    }
                }
            }

            // 添加類型顯示標籤
            $trip->type_label = $trip->isGoldenHour() ? __('Golden Hour') : ($trip->type === 'fixed' ? __('Fixed Price') : __('Regular'));

            // 檢查 booking 是否已截止（但 trip 本身還未過 departure time）
            $departureTime = $dt->copy();
            if ($trip->type === 'golden') {
                // Golden hour: departure time 前 1小時截止預訂
                $bookingDeadline = $departureTime->subHour();
                $trip->is_expired = $now->gte($bookingDeadline);
            } else {
                // Normal trip: departure time 前 48小時截止預訂  
                $bookingDeadline = $departureTime->subHours(48);
                $trip->is_expired = $now->gte($bookingDeadline);
            }

            // 添加調試信息（可在開發時使用）
            $trip->debug_info = [
                'now' => $now->format('Y-m-d H:i:s T'),
                'departure' => $dt->format('Y-m-d H:i:s T'),
                'booking_deadline' => isset($bookingDeadline) ? $bookingDeadline->format('Y-m-d H:i:s T') : null,
                'is_expired' => $trip->is_expired
            ];

            // 添加優先級排序用的欄位
            $trip->sort_priority = $trip->type === 'golden' ? 0 : 1;

            return $trip;
        })->groupBy('date')->map(function ($dayTrips) {
            // 在每日內重新排序：golden hour 在前，然後按時間排序
            return $dayTrips->sortBy([
                ['sort_priority', 'asc'],
                ['planned_departure_time', 'asc']
            ])->values();
        });
        $dates = $groupedTrips->keys()->sort();
        $dateList = $dates->map(function ($date) {
            return [
                'label' => Carbon::parse($date)->format('D M d'),
                'value' => $date
            ];
        });


        // 設定預設的 active date
        $requestedDate = $request->get('date');
        $activeDate = $requestedDate && $dates->contains($requestedDate)
            ? $requestedDate
            : ($dates->first() ?? $now->format('Y-m-d'));

        $trips = Trip::paginate(10);

        return view('dashboard', [
            'groupedTrips' => $groupedTrips,
            'dates' => $dates,
            'dateList' => $dateList,
            'activeDate' => $activeDate,
            'trips' => $trips,
        ]);
    }

    /**
     * Join a trip
     */
    // public function join(Request $request, Trip $trip)
    // {
    //     $user = Auth::user();

    //     // 檢查用戶是否已經加入
    //     $existingJoin = $trip->joins()->where('user_id', $user->id)->first();
    //     if ($existingJoin) {
    //         return redirect()->back()->with('error', __('You have already joined this trip.'));
    //     }

    //     // 檢查行程是否已滿
    //     if ($trip->joins()->count() >= $trip->max_people) {
    //         return redirect()->back()->with('error', __('This trip is full.'));
    //     }

    //     // 檢查行程狀態
    //     if ($trip->trip_status !== 'awaiting') {
    //         return redirect()->back()->with('error', __('This trip is no longer available for joining.'));
    //     }

    //     // 驗證表單數據
    //     $validated = $request->validate([
    //         'pickup_location' => 'nullable|string|max:255'
    //     ]);

    //     // 計算用戶費用（基於當前人數動態計算）
    //     $currentPeople = $trip->joins()->count();
    //     $totalCost = $trip->base_price; // 從數據庫獲取基礎費用
    //     $newUserFee = $totalCost / ($currentPeople + 1); // +1 因為包括即將加入的用戶

    //     // 創建加入記錄
    //     $trip->joins()->create([
    //         'user_id' => $user->id,
    //         'pickup_location' => $validated['pickup_location'] ?? null,
    //         'join_role' => 'normal',
    //         'user_fee' => round($newUserFee, 2),
    //         'vote_info' => json_encode([])
    //     ]);

    //     // 更新所有現有成員的費用（包括新加入的用戶）
    //     $newPeopleCount = $currentPeople + 1;
    //     $updatedUserFee = $totalCost / $newPeopleCount;

    //     DB::table('trip_joins')
    //         ->where('trip_id', $trip->id)
    //         ->update(['user_fee' => round($updatedUserFee, 2)]);

    //     return redirect()->back()->with('success', __('Successfully joined the trip!'));
    // }

    /**
     * Leave a trip
     */
    public function leave(Trip $trip)
    {
        $user = Auth::user();

        $deleted = $trip->joins()->where('user_id', $user->id)->delete();

        if (!$deleted) {
            return redirect()->back()->with('error', __('You are not a member of this trip.'));
        }

        // 重新計算剩餘成員的費用
        $remainingPeople = $trip->joins()->count();
        if ($remainingPeople > 0) {
            $totalCost = $trip->base_price;
            $updatedUserFee = $totalCost / $remainingPeople;

            DB::table('trip_joins')
                ->where('trip_id', $trip->id)
                ->update(['user_fee' => round($updatedUserFee, 2)]);
        }

        return redirect()->back()->with('success', __('Successfully left the trip.'));
    }

    /**
     * Depart immediately (when only one person)
     */
    public function departNow(Trip $trip)
    {
        $user = Auth::user();

        // 檢查用戶是否已加入
        $join = $trip->joins()->where('user_id', $user->id)->first();
        if (!$join) {
            return redirect()->back()->with('error', __('You are not a member of this trip.'));
        }

        // 更新行程狀態
        $trip->update([
            'trip_status' => 'departed',
            'actual_departure_time' => now()
        ]);

        return redirect()->back()->with('success', __('Trip has departed successfully!'));
    }


}

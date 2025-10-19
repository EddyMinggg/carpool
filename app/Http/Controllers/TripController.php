<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TripJoin;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TripMemberLeaveNotification;
use Notification;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get payments for the current user
        $tripJoins = TripJoin::with(['trip', 'user'])
            ->where('user_phone', Auth::user()->phone)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('trips.index', compact('tripJoins'));
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

        $payment = Payment::where('trip_id', $id)
            ->where('user_phone', $userPhone)
            ->first();

        $tripJoin = TripJoin::where('trip_id', $id)
            ->where('user_phone', $userPhone)
            ->first();
        $hasLeft = $tripJoin?->has_left ?? false;

        $madePayment = null;

        // Always use individual user_fee from TripJoin, not payment amount
        // This ensures we show per-person fee even for group bookings
        $price = null;
        if ($tripJoin) {
            $price = $tripJoin->user_fee;
        }

        if ($payment) {
            $madePayment = $payment->paid;
            // Only use payment amount if no TripJoin record exists
            if ($price === null) {
                $price = $payment->amount;
            }
        }

        // 只有當付款未完成且用戶未離開且行程仍在進行中時才跳轉到付款頁面
        if ($madePayment !== null && !$madePayment && !$hasLeft) {
            $trip = Trip::findOrFail($id);
            // 檢查行程是否已過期或已完成
            $isExpired = $trip->planned_departure_time < Carbon::now();
            $isCompleted = in_array($trip->trip_status, ['departed', 'completed']);

            // 只有未過期且未完成的行程才跳轉到付款頁面
            if (!$isExpired && !$isCompleted) {
                return redirect()->route('payment.code', ['id' => $payment->id]);
            }
        }

        $trip = Trip::with(['joins.user', 'creator'])->findOrFail($id);

        // 計算當前用戶是否已加入且payment已確認
        $userJoin = $trip->activeJoins->where('user_phone', $userPhone)->first();
        $hasJoined = $userJoin !== null && $userJoin->payment_confirmed;

        // 如果有payment記錄且已付款，但TripJoin記錄未確認，說明管理員還未處理
        $hasPaidButNotConfirmed = $payment && $payment->paid && $userJoin && !$userJoin->payment_confirmed;

        // 計算當前有效的人數（包含已確認付款 + 30分鐘內未付款）
        // 使用新的方法來計算有效占位數
        $currentPeople = $trip->getValidOccupiedSlotsCount();

        // Golden 或 Normal 類型：根據人數和類型計算價格
        $peopleCount = max(1, $currentPeople); // 至少1人

        if ($trip->type === 'golden') {
            // 黃金時段：固定每人250，最少1人
            $userFee = $trip->price_per_person; // 250
        } else {
            // 普通時段：每人275，4人有折扣
            if ($peopleCount >= 4 && $trip->four_person_discount > 0) {
                $userFee = $trip->price_per_person - $trip->four_person_discount;
            } else {
                $userFee = $trip->price_per_person; // 275
            }
        }

        // 格式化時間
        $departureTime = Carbon::parse($trip->planned_departure_time);

        // 計算可用槽位數（使用新的方法，考慮30分鐘超時）
        $availableSlots = $trip->getAvailableSlots();

        // 獲取分配的司機信息
        $assignedDriver = null;
        if ($trip->tripDriver && in_array($trip->tripDriver->status, ['assigned', 'confirmed'])) {
            $assignedDriver = $trip->getDriver();
        }

        $isGroupBooking = $payment && $payment->type === 'group';
        $showInvitationCode = ($hasJoined || (isset($hasPaidButNotConfirmed) && $hasPaidButNotConfirmed)) && $isGroupBooking;

        // 獲取有效的成員列表（已確認付款 + 30分鐘內未付款的）
        $validMembers = $trip->joins()
            ->where('has_left', 0)
            ->where(function ($query) {
                $query->where('payment_confirmed', 1) // 已確認付款
                    ->orWhere(function ($q) {
                        $q->where('payment_confirmed', 0) // 或未付款但在30分鐘內
                            ->where('created_at', '>=', now()->subMinutes(30));
                    });
            })
            ->with('user')
            ->get();

        return view('trips.show', compact(
            'trip',
            'userPhone',
            'hasJoined',
            'hasLeft',
            'hasPaidButNotConfirmed',
            'currentPeople',
            'availableSlots',
            'userFee',
            'showInvitationCode',
            'isGroupBooking',
            'price',
            'departureTime',
            'assignedDriver',
            'validMembers'
        ));
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

        // 只顯示未來2週內且未過 departure time 的行程 - 加载 activeJoins 以正确计算人数
        $trips = Trip::with('activeJoins')
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
            
            // 使用新的方法計算有效人數（已確認 + 30分鐘內未付款）
            $trip->current_people = $trip->getValidOccupiedSlotsCount();

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

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', __('Please login to continue.'));
        }

        // Get user phone
        $userPhone = $user->phone;

        if (!$userPhone) {
            return redirect()->back()->with('error', __('Unable to identify user phone number.'));
        }

        // Find the trip join record
        $tripJoin = $trip
            ->joins()
            ->where('user_phone', $userPhone)
            ->first();

        // Check if user is actually a member of this trip
        if (!$tripJoin) {
            return redirect()->back()->with('error', __('You are not a member of this trip.'));
        }

        // Check if already left
        if ($tripJoin->has_left) {
            return redirect()->back()->with('error', __('You have already left this trip.'));
        }

        // Update the has_left status
        $tripJoin->update(['has_left' => 1]);

        // Get user display name for notification
        $leftUserName = $user->username ?? null;

        // Notify other members
        $otherUserPhone = $trip
            ->joins()
            ->whereNot('user_phone', $userPhone)
            ->whereNot('has_left', 1) // Only notify active members
            ->pluck('user_phone');

        // Get user message channel preference
        foreach ($otherUserPhone as $phone) {
            Notification::route('Sms', $phone)
                ->notify(new TripMemberLeaveNotification($trip, $userPhone, $leftUserName));
        }

        return redirect()->route('trips')->with('success', __('Successfully left the trip.'));
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

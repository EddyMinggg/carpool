<?php

namespace App\Http\Controllers;

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
        $tripJoins = TripJoin::where('user_id', Auth::user()->id)->paginate(10);

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
    public function show(string $id)
    {
        $trip = Trip::with(['joins.user', 'creator'])->findOrFail($id);

        // 計算當前用戶是否已加入
        $currentUser = Auth::user();
        $userJoin = $trip->joins->where('user_id', $currentUser->id)->first();
        $hasJoined = $userJoin !== null;

        // 計算價格（根據用戶是否已加入）
        $currentPeople = $trip->joins->count();

        if ($hasJoined) {
            // 已加入用戶：顯示當前分攤價格
            if ($currentPeople <= 1) {
                $price = $trip->base_price; // 只有自己時顯示全額
            } else {
                $price = round($trip->base_price / $currentPeople); // 顯示當前分攤
            }
        } else {
            // 未加入用戶：顯示加入後的價格
            $futureCurrentPeople = $currentPeople + 1; // 假設用戶加入後的人數
            $price = round($trip->base_price / $futureCurrentPeople);
        }

        // 檢查是否有進行中的投票
        $currentVote = null;
        $userVoteStatus = 'pending';
        if ($hasJoined && $userJoin && $trip->trip_status === 'voting') {
            $currentVote = true;
            $voteInfo = $userJoin->vote_info; // Already cast to array

            // 檢查是否已經投票：必須是非空數組且有有效的vote_result
            if (
                is_array($voteInfo) &&
                isset($voteInfo['vote_result']) &&
                in_array($voteInfo['vote_result'], ['agree', 'disagree'])
            ) {
                $userVoteStatus = $voteInfo['vote_result'];
            }
        }

        // 格式化時間
        $departureTime = Carbon::parse($trip->planned_departure_time);
        $timeUntilDeparture = - ($departureTime->diffInMinutes(now(), false));

        return view('trips.show_mobile', compact(
            'trip',
            'hasJoined',
            'currentPeople',
            'price',
            'currentVote',
            'userVoteStatus',
            'timeUntilDeparture',
            'departureTime'
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
        $trips = Trip::with('joins')->orderBy('planned_departure_time')->get();
        $groupedTrips = $trips->filter(function ($trip) {
            return !empty($trip->planned_departure_time);
        })->map(function ($trip) {
            $dt = $trip->planned_departure_time instanceof Carbon
                ? $trip->planned_departure_time
                : Carbon::parse($trip->planned_departure_time);
            $trip->date = $dt->format('Y-m-d');
            $trip->formatted_departure_time = $dt->format('H:i');
            $trip->current_people = isset($trip->joins) ? $trip->joins->count() : 0;
            // 金額計算：顯示當前狀態下每人的費用
            $totalCost = $trip->base_price;
            if ($trip->current_people <= 1) {
                $trip->price = $totalCost; // 1人或以下時顯示全額
            } else {
                $trip->price = round($totalCost / $trip->current_people); // 多人時顯示當前分攤
            }
            return $trip;
        })->groupBy('date');
        $dates = $groupedTrips->keys()->sort();
        $dateList = $dates->map(function ($date) {
            return [
                'label' => Carbon::parse($date)->format('D M d'),
                'value' => $date
            ];
        });


        $trips = Trip::paginate(10);

        return view('dashboard', [
            'groupedTrips' => $groupedTrips,
            'dates' => $dates,
            'dateList' => $dateList,
            'trips' => $trips,
        ]);
    }

    /**
     * Join a trip
     */
    public function join(Request $request, Trip $trip)
    {
        $user = Auth::user();

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

        // 驗證表單數據
        $validated = $request->validate([
            'pickup_location' => 'nullable|string|max:255'
        ]);

        // 計算用戶費用（基於當前人數動態計算）
        $currentPeople = $trip->joins()->count();
        $totalCost = $trip->base_price; // 從數據庫獲取基礎費用
        $newUserFee = $totalCost / ($currentPeople + 1); // +1 因為包括即將加入的用戶

        // 創建加入記錄
        $trip->joins()->create([
            'user_id' => $user->id,
            'pickup_location' => $validated['pickup_location'] ?? null,
            'join_role' => 'normal',
            'user_fee' => round($newUserFee, 2),
            'vote_info' => json_encode([])
        ]);

        // 更新所有現有成員的費用（包括新加入的用戶）
        $newPeopleCount = $currentPeople + 1;
        $updatedUserFee = $totalCost / $newPeopleCount;

        DB::table('trip_joins')
            ->where('trip_id', $trip->id)
            ->update(['user_fee' => round($updatedUserFee, 2)]);

        return redirect()->back()->with('success', __('Successfully joined the trip!'));
    }

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

        // 檢查是否只有一個人
        $memberCount = $trip->joins()->count();
        if ($memberCount > 1) {
            return redirect()->back()->with('error', __('Cannot depart immediately when there are multiple members. Please start a vote instead.'));
        }

        // 更新行程狀態
        $trip->update([
            'trip_status' => 'departed',
            'actual_departure_time' => now()
        ]);

        return redirect()->back()->with('success', __('Trip has departed successfully!'));
    }

    /**
     * Start a vote to depart
     */
    public function startVote(Trip $trip)
    {
        $user = Auth::user();

        // 調試：記錄原始planned_departure_time
        Log::info('Starting vote - Original planned_departure_time: ' . $trip->planned_departure_time);

        // 檢查用戶是否已加入
        $userJoin = $trip->joins()->where('user_id', $user->id)->first();
        if (!$userJoin) {
            return redirect()->back()->with('error', __('You must be a member to start a vote.'));
        }

        // 檢查行程狀態
        if ($trip->trip_status !== 'pending') {
            return redirect()->back()->with('error', __('Cannot start vote. Trip is not in pending status.'));
        }

        // 檢查是否已有投票進行中
        if ($trip->trip_status === 'voting') {
            return redirect()->back()->with('error', __('A vote is already in progress.'));
        }

        // 檢查成員數量 - 只有一個人時不應該能發起投票
        $memberCount = $trip->joins->count();
        if ($memberCount <= 1) {
            return redirect()->back()->with('error', __('Cannot start vote with only one member. Use "Depart Now" instead.'));
        }

        // 調試：記錄更新前的planned_departure_time
        Log::info('Before updating trip status - planned_departure_time: ' . $trip->planned_departure_time);

        // 更新行程狀態為投票中（只更新trip_status）
        $trip->update(['trip_status' => 'voting']);

        // 調試：記錄更新後的planned_departure_time
        $trip->refresh();
        Log::info('After updating trip status - planned_departure_time: ' . $trip->planned_departure_time);

        // 使用明確的where條件來更新，避免複合主鍵問題
        // 清除其他用戶的投票數據
        DB::table('trip_joins')
            ->where('trip_id', $trip->id)
            ->where('user_id', '!=', $user->id)
            ->update(['vote_info' => null]);

        // 給發起投票的用戶設置投票狀態
        DB::table('trip_joins')
            ->where('trip_id', $trip->id)
            ->where('user_id', $user->id)
            ->update([
                'vote_info' => json_encode([
                    'vote_result' => 'agree',
                    'vote_time' => now()->toISOString()
                ])
            ]);

        // 調試：記錄最終的planned_departure_time
        $trip->refresh();
        Log::info('Final planned_departure_time: ' . $trip->planned_departure_time);

        // 不立即檢查投票完成 - 等待其他人投票
        // $this->checkVoteCompletion($trip);

        return redirect()->back()->with('success', __('Vote started! You automatically voted to agree. Waiting for other members to vote.'));
    }

    /**
     * Cast a vote
     */
    public function vote(Trip $trip, Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'vote_result' => 'required|in:agree,disagree'
        ]);

        // 檢查用戶是否已加入
        $userJoin = $trip->joins()->where('user_id', $user->id)->first();
        if (!$userJoin) {
            return redirect()->back()->with('error', __('You must be a member to vote.'));
        }

        // 檢查是否有投票進行中
        if ($trip->trip_status !== 'voting') {
            return redirect()->back()->with('error', __('No active vote found.'));
        }

        // 更新用戶投票 - 使用明確的where條件
        DB::table('trip_joins')
            ->where('trip_id', $trip->id)
            ->where('user_id', $user->id)
            ->update([
                'vote_info' => json_encode([
                    'vote_result' => $request->vote_result,
                    'vote_time' => now()->toISOString()
                ])
            ]);

        // 檢查是否所有人都已投票
        $this->checkVoteCompletion($trip);

        return redirect()->back()->with('success', __('Vote submitted successfully!'));
    }

    /**
     * Check if voting is complete and process results
     */
    private function checkVoteCompletion(Trip $trip)
    {
        $joins = $trip->joins()->get();
        $totalMembers = $joins->count();
        $votedMembers = 0;
        $agreeVotes = 0;

        foreach ($joins as $join) {
            $voteInfo = $join->vote_info; // Already cast to array by model
            // 檢查是否已經投票：必須是非空數組且有有效的vote_result
            if (
                is_array($voteInfo) &&
                isset($voteInfo['vote_result']) &&
                in_array($voteInfo['vote_result'], ['agree', 'disagree'])
            ) {
                $votedMembers++;
                if ($voteInfo['vote_result'] === 'agree') {
                    $agreeVotes++;
                }
            }
        }

        // 如果所有人都已投票，處理結果
        if ($votedMembers === $totalMembers) {
            $majority = ceil($totalMembers / 2);

            if ($agreeVotes >= $majority) {
                // 投票通過，立即出發
                $trip->update([
                    'trip_status' => 'departed',
                    'actual_departure_time' => now()
                ]);
            } else {
                // 投票未通過，回到pending狀態
                $trip->update(['trip_status' => 'pending']);

                // 清除投票信息
                foreach ($trip->joins as $join) {
                    $join->update(['vote_info' => []]);
                }
            }
        }
    }
}

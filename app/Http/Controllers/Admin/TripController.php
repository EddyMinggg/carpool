<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripJoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $trips = Trip::with(['creator', 'activeJoins.user'])
            ->orderBy('planned_departure_time', 'desc')
            ->paginate(10);

        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        // Return JSON for AJAX requests
        if ($request->wantsJson()) {
            return response()->json(
                Trip::with(['creator', 'activeJoins'])
                    ->orderBy('planned_departure_time', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function ($trip) {
                        return [
                            'id' => $trip->id,
                            'dropoff_location' => $trip->dropoff_location,
                            'planned_departure_time' => $trip->planned_departure_time->format('M d, H:i'),
                            'joins_count' => $trip->activeJoins->count(),
                            'max_people' => $trip->max_people,
                            'trip_status' => $trip->trip_status,
                            'creator' => $trip->creator->username ?? 'Unknown'
                        ];
                    })
            );
        }

        return view('admin.trips.index', compact('trips', 'isMobile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        return view('admin.trips.create', compact('isMobile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if this is a batch creation
        if ($request->has('batch_trips') && !empty($request->batch_trips)) {
            return $this->storeBatchTrips($request);
        }

        // Single trip creation
        $validated = $request->validate([
            'dropoff_location' => 'required|string|max:255',
            'planned_departure_time' => 'required|date|after:now',
            'max_people' => 'required|integer|min:1|max:8',
            'price_per_person' => 'required|numeric|min:0',
            'type' => 'required|in:golden,normal,fixed',
            'four_person_discount' => 'nullable|numeric|min:0',
            'trip_status' => 'required|in:awaiting,departed,charging,completed,cancelled'
        ]);

        $trip = $this->createSingleTrip($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip created successfully!');
    }

    private function storeBatchTrips(Request $request)
    {
        $validated = $request->validate([
            'dropoff_location' => 'required|string|max:255',
            'trip_status' => 'required|in:awaiting,departed,charging,completed,cancelled',
            'batch_trips' => 'required|array|min:1',
            'batch_trips.*.departure_time' => 'required|date|after:now',
            'batch_trips.*.type' => 'required|in:golden,normal',
            'batch_trips.*.price_per_person' => 'required|numeric|min:0',
            'batch_trips.*.max_people' => 'required|integer|min:1|max:8',
            'batch_trips.*.four_person_discount' => 'nullable|numeric|min:0',
        ]);

        $createdTrips = 0;

        foreach ($validated['batch_trips'] as $batchTrip) {
            $tripData = [
                'dropoff_location' => $validated['dropoff_location'],
                'planned_departure_time' => $batchTrip['departure_time'],
                'max_people' => $batchTrip['max_people'],
                'price_per_person' => $batchTrip['price_per_person'],
                'type' => $batchTrip['type'], // 使用 type 而非 is_golden_hour
                'trip_status' => $validated['trip_status'],
                'four_person_discount' => $batchTrip['four_person_discount'] ?? null,
            ];

            $this->createSingleTrip($tripData);
            $createdTrips++;
        }

        return redirect()->route('admin.trips.index')
            ->with('success', "Successfully created {$createdTrips} trips!");
    }

    private function createSingleTrip($data)
    {
        // 根據時段類型自動設置業務邏輯參數
        $isGoldenHour = ($data['type'] === 'golden');

        if ($isGoldenHour) {
            // 黃金時段：1人即可出發，無優惠
            $minPassengers = 1;
            $fourPersonDiscount = 0.00;
        } else {
            // 普通時段：2人起，可以有4人優惠
            $minPassengers = 2;
            $fourPersonDiscount = $data['four_person_discount'] ?? 50.00;
        }

        return Trip::create([
            'creator_id' => Auth::id(),
            'dropoff_location' => $data['dropoff_location'],
            'planned_departure_time' => $data['planned_departure_time'],
            'max_people' => $data['max_people'],
            'price_per_person' => $data['price_per_person'],
            'type' => $data['type'], // 直接使用 type
            'min_passengers' => $minPassengers,
            'four_person_discount' => $fourPersonDiscount,
            'trip_status' => $data['trip_status'],
            'invitation_code' => bin2hex(random_bytes(4)),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $trip->load(['creator', 'joins.user', 'payments.user']);

        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        return view('admin.trips.show', compact('trip', 'isMobile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        $users = \App\Models\User::all();

        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        return view('admin.trips.edit', compact('trip', 'users', 'isMobile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'creator_id' => 'required|exists:users,id',
            'dropoff_location' => 'required|string|max:255',
            'planned_departure_time' => 'required|date',
            'max_people' => 'required|integer|min:1|max:10',
            'min_passengers' => 'required|integer|min:1|max:10',
            'price_per_person' => 'required|numeric|min:0',
            'four_person_discount' => 'nullable|numeric|min:0',
            'type' => 'required|in:normal,golden',
            'trip_status' => 'required|in:awaiting,departed,charging,completed,cancelled'
        ]);


        $trip->update($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        // 檢查是否有活跃成员（未离开的）
        $activeJoinsCount = $trip->activeJoins()->count();
        
        if ($activeJoinsCount > 0) {
            return redirect()->route('admin.trips.index')
                ->with('error', "Cannot delete this trip. There are {$activeJoinsCount} active booking(s) for this trip. Please cancel all bookings first.");
        }

        // 沒有預訂，可以刪除
        // 使用 forceDelete() 來永久刪除記錄
        $trip->forceDelete();

        return redirect()->route('admin.trips.index')->with('success', 'Trip deleted successfully!');
    }

    public function dashboard()
    {
        $currentDate = request('date', today()->format('Y-m-d'));
        $user = Auth::user();

        $trips = Trip::with(['activeJoins.user'])
            ->whereDate('planned_departure_time', $currentDate)
            ->where('trip_status', 'awaiting')
            ->get()
            ->map(function ($trip) use ($user) {
                $currentPeople = $trip->activeJoins()->count();
                $userHasJoined = $trip->activeJoins()->where('user_id', $user->id)->exists();

                if ($currentPeople <= 1) {
                    $price = $trip->base_price;
                } else {
                    if ($userHasJoined) {
                        $price = round($trip->base_price / $currentPeople);
                    } else {
                        $price = round($trip->base_price / ($currentPeople + 1));
                    }
                }

                return [
                    'id' => $trip->id,
                    'pickup_location' => $trip->pickup_location,
                    'dropoff_location' => $trip->dropoff_location,
                    'planned_departure_time' => $trip->planned_departure_time,
                    'max_people' => $trip->max_people,
                    'current_people' => $currentPeople,
                    'price' => $price,
                    'trip_status' => $trip->trip_status,
                    'user_has_joined' => $userHasJoined,
                ];
            });

        return view('dashboard', compact('trips', 'currentDate'));
    }

    public function join(Trip $trip, Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($trip->trip_status !== 'awaiting') {
            return redirect()->back()->with('error', 'This trip is not available for joining.');
        }

        $existingJoin = $trip->activeJoins()->where('user_id', $user->id)->first();
        if ($existingJoin) {
            return redirect()->back()->with('error', 'You have already joined this trip.');
        }

        // 使用新的方法檢查可用槽位（考慮30分鐘超時）
        $availableSlots = $trip->getAvailableSlots();
        if ($availableSlots <= 0) {
            return redirect()->back()->with('error', 'This trip is already full.');
        }

        // 計算有效人數用於定價
        $currentPeople = $trip->getValidOccupiedSlotsCount();
        $totalCost = $trip->base_price;
        $userFee = $totalCost / ($currentPeople + 1);

        $trip->joins()->create([
            'user_id' => $user->id,
            'pickup_location' => $request->pickup_location,
            'join_role' => 'normal',
            'user_fee' => $userFee
        ]);

        $this->updateAllUserFees($trip);

        return redirect()->back()->with('success', 'Successfully joined the trip!');
    }

    public function leave(Trip $trip)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $deleted = $trip->joins()->where('user_id', $user->id)->delete();

        if ($deleted) {
            $this->updateAllUserFees($trip);
            return redirect()->back()->with('success', 'Successfully left the trip.');
        } else {
            return redirect()->back()->with('error', 'You are not a member of this trip.');
        }
    }

    public function departNow(Trip $trip)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $userHasJoined = $trip->activeJoins()->where('user_id', $user->id)->exists();
        if (!$userHasJoined) {
            return redirect()->back()->with('error', 'You must join the trip first.');
        }

        if ($trip->trip_status !== 'awaiting') {
            return redirect()->back()->with('error', 'Trip cannot depart at this time.');
        }

        $trip->update([
            'trip_status' => 'departed',
            'actual_departure_time' => now()
        ]);

        return redirect()->back()->with('success', 'Trip has departed successfully!');
    }

    /**
     * Show trips that need admin review (2 passengers for normal type)
     */
    public function pendingReview()
    {
        $now = Carbon::now();
        
        // Find normal trips with exactly 2 confirmed passengers that are past deadline
        $pendingTrips = Trip::where('trip_status', Trip::STATUS_AWAITING)
            ->where('type', Trip::TYPE_NORMAL)
            ->with(['activeJoins.user', 'creator'])
            ->get()
            ->filter(function ($trip) use ($now) {
                // Check if deadline passed (48 hours before departure)
                $deadline = Carbon::parse($trip->planned_departure_time)->subHours(48);
                if (!$now->greaterThanOrEqualTo($deadline)) {
                    return false;
                }
                
                // Check if exactly 2 confirmed passengers
                $confirmedCount = $trip->activeJoins()
                    ->where('payment_confirmed', 1)
                    ->count();
                
                return $confirmedCount === 2;
            });

        return view('admin.trips.pending-review', compact('pendingTrips'));
    }

    /**
     * Confirm trip with surcharge for 2-passenger trips
     */
    public function confirmWithSurcharge(Trip $trip, Request $request)
    {
        $request->validate([
            'surcharge_amount' => 'required|numeric|min:0',
            'action' => 'required|in:confirm,cancel'
        ]);

        if ($trip->type !== Trip::TYPE_NORMAL) {
            return redirect()->back()->with('error', 'Only normal trips can use this function.');
        }

        $confirmedCount = $trip->activeJoins()
            ->where('payment_confirmed', 1)
            ->count();

        if ($confirmedCount !== 2) {
            return redirect()->back()->with('error', 'This trip does not have exactly 2 passengers.');
        }

        if ($request->action === 'cancel') {
            // Admin decided to cancel the trip
            $trip->update(['trip_status' => Trip::STATUS_CANCELLED]);
            
            return redirect()->back()->with('success', 'Trip has been cancelled.');
        }

        // Admin confirmed the trip with surcharge
        // Update price for all active joins
        $surchargePerPerson = $request->surcharge_amount;
        
        foreach ($trip->activeJoins()->where('payment_confirmed', 1)->get() as $join) {
            $newFee = $join->user_fee + $surchargePerPerson;
            $join->update(['user_fee' => $newFee]);
        }

        // Update trip status to departed if departure time reached, otherwise keep awaiting
        if (Carbon::now()->greaterThanOrEqualTo($trip->planned_departure_time)) {
            $trip->update(['trip_status' => Trip::STATUS_DEPARTED]);
            $message = "Trip confirmed with surcharge of \${$surchargePerPerson} per person and marked as departed.";
        } else {
            // Keep as awaiting but add a note that admin approved
            $message = "Trip confirmed with surcharge of \${$surchargePerPerson} per person. Will depart as scheduled.";
        }

        return redirect()->back()->with('success', $message);
    }

    private function updateAllUserFees(Trip $trip)
    {
        $currentPeople = $trip->activeJoins()->count();

        if ($currentPeople > 0) {
            $newFee = $trip->base_price / $currentPeople;

            DB::table('trip_joins')
                ->where('trip_id', $trip->id)
                ->where('has_left', 0)  // 只更新未离开的成员
                ->update(['user_fee' => $newFee]);
        }
    }
}

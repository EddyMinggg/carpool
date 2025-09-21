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
    public function index()
    {
        $trips = Trip::with(['creator', 'joins.user'])
            ->orderBy('planned_departure_time', 'desc')
            ->paginate(10);

        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.trips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dropoff_location' => 'required|string|max:255',
            'planned_departure_time' => 'required|date|after:now',
            'max_people' => 'required|integer|min:1|max:8',
            'base_price' => 'required|numeric|min:0',
            'trip_status' => 'required|in:awaiting,pending,voting,departed,completed,cancelled'
        ]);

        $trip = Trip::create([
            'creator_id' => Auth::id(),
            'pickup_location' => null, // 管理員創建時不設定上車地點
            'dropoff_location' => $validated['dropoff_location'],
            'planned_departure_time' => $validated['planned_departure_time'],
            'max_people' => $validated['max_people'],
            'base_price' => $validated['base_price'],
            'trip_status' => $validated['trip_status']
        ]);

        return redirect()->route('admin.trips.index')->with('success', 'Trip created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $trip->load(['creator', 'joins.user']);
        return view('admin.trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        $users = \App\Models\User::all();
        return view('admin.trips.edit', compact('trip', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'dropoff_location' => 'required|string|max:255',
            'planned_departure_time' => 'required|date',
            'max_people' => 'required|integer|min:1|max:8',
            'base_price' => 'required|numeric|min:0',
            'trip_status' => 'required|in:awaiting,pending,voting,departed,completed,cancelled'
        ]);

        $trip->update($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Trip updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        // 删除相关的 trip_joins 记录
        $trip->joins()->delete();

        // 删除 trip 记录
        $trip->delete();

        return redirect()->route('admin.trips.index')->with('success', 'Trip deleted successfully!');
    }

    public function dashboard()
    {
        $currentDate = request('date', today()->format('Y-m-d'));
        $user = Auth::user();

        $trips = Trip::with(['joins.user'])
            ->whereDate('planned_departure_time', $currentDate)
            ->where('trip_status', 'awaiting')
            ->get()
            ->map(function ($trip) use ($user) {
                $currentPeople = $trip->joins()->count();
                $userHasJoined = $trip->joins()->where('user_id', $user->id)->exists();

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

        $existingJoin = $trip->joins()->where('user_id', $user->id)->first();
        if ($existingJoin) {
            return redirect()->back()->with('error', 'You have already joined this trip.');
        }

        $currentPeople = $trip->joins()->count();
        if ($currentPeople >= $trip->max_people) {
            return redirect()->back()->with('error', 'This trip is already full.');
        }

        $totalCost = $trip->base_price;
        $userFee = $totalCost / ($currentPeople + 1);

        $trip->joins()->create([
            'user_id' => $user->id,
            'pickup_location' => $request->pickup_location,
            'join_role' => 'normal',
            'user_fee' => $userFee,
            'vote_info' => null
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

        $userHasJoined = $trip->joins()->where('user_id', $user->id)->exists();
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

    private function updateAllUserFees(Trip $trip)
    {
        $currentPeople = $trip->joins()->count();

        if ($currentPeople > 0) {
            $newFee = $trip->base_price / $currentPeople;

            DB::table('trip_joins')
                ->where('trip_id', $trip->id)
                ->update(['user_fee' => $newFee]);
        }
    }
}

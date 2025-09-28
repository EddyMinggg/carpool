<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripDriver;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    // 移除构造函数中的中间件，因为我们已经在路由中处理了认证

    /**
     * Driver dashboard - show available and assigned trips
     */
    public function dashboard(Request $request)
    {
        $driver = Auth::user();
        
        // Check if user is a driver
        if (!$driver || !$driver->isDriver()) {
            abort(403, 'Access denied. Driver role required.');
        }
        
        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        // Get available trips (no driver assigned yet, status awaiting)
        $availableTrips = Trip::with(['creator', 'joins.user'])
            ->where('trip_status', 'awaiting')
            ->where('planned_departure_time', '>', Carbon::now())
            ->whereDoesntHave('tripDriver') // No driver assigned yet
            ->orderBy('planned_departure_time', 'asc')
            ->paginate(10, ['*'], 'available');



        return view('driver.dashboard', compact('availableTrips'));
    }

    /**
     * Assign driver to a trip
     */
    public function assignTrip(Request $request, Trip $trip)
    {
        $driver = Auth::user();

        // Check if trip is available
        if ($trip->trip_status !== 'awaiting' || $trip->hasDriver()) {
            return back()->with('error', 'This trip is no longer available.');
        }

        // Check if driver has conflicting trips (within 3 hours of each other)
        $conflictingTrips = TripDriver::where('driver_id', $driver->id)
            ->whereHas('trip', function ($query) use ($trip) {
                $start = $trip->planned_departure_time->subHours(3);
                $end = $trip->planned_departure_time->addHours(3);
                $query->whereBetween('planned_departure_time', [$start, $end]);
            })
            ->whereIn('status', ['assigned', 'confirmed'])
            ->exists();

        if ($conflictingTrips) {
            return back()->with('error', 'You have conflicting trips within 3 hours of this departure time.');
        }

        try {
            DB::beginTransaction();

            // Create trip-driver assignment
            TripDriver::create([
                'trip_id' => $trip->id,
                'driver_id' => $driver->id,
                'status' => 'assigned',
                'notes' => $request->notes,
                'assigned_at' => Carbon::now(),
            ]);

            DB::commit();

            return back()->with('success', "Successfully assigned to trip #{$trip->id} to {$trip->dropoff_location}");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to assign trip. Please try again.');
        }
    }

    /**
     * Confirm driver assignment
     */
    public function confirmTrip(TripDriver $tripDriver)
    {
        $driver = Auth::user();

        if ($tripDriver->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($tripDriver->status !== 'assigned') {
            return back()->with('error', 'Trip cannot be confirmed in current status.');
        }

        $tripDriver->update([
            'status' => 'confirmed',
            'confirmed_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Trip confirmed successfully!');
    }

    /**
     * Cancel driver assignment
     */
    public function cancelTrip(TripDriver $tripDriver)
    {
        $driver = Auth::user();

        if ($tripDriver->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$tripDriver->canCancel()) {
            return back()->with('error', 'Trip cannot be cancelled in current status.');
        }

        // Check if it's too close to departure time (less than 2 hours)
        $hoursUntilDeparture = Carbon::now()->diffInHours($tripDriver->trip->planned_departure_time, false);
        if ($hoursUntilDeparture < 2 && $hoursUntilDeparture > 0) {
            return back()->with('error', 'Cannot cancel trip less than 2 hours before departure.');
        }

        $tripDriver->update(['status' => 'cancelled']);

        return back()->with('success', 'Trip assignment cancelled.');
    }

    /**
     * Mark trip as completed
     */
    public function completeTrip(TripDriver $tripDriver)
    {
        $driver = Auth::user();

        if ($tripDriver->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($tripDriver->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed trips can be completed.');
        }

        DB::beginTransaction();
        try {
            // Update trip status
            $tripDriver->update(['status' => 'completed']);
            
            // Update the trip status as well
            $tripDriver->trip->update(['trip_status' => 'completed']);

            DB::commit();
            return back()->with('success', 'Trip marked as completed!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to complete trip. Please try again.');
        }
    }

    /**
     * Driver my trips page - show only assigned trips
     */
    public function myTrips(Request $request)
    {
        $driver = Auth::user();
        
        // Check if user is a driver
        if (!$driver || !$driver->isDriver()) {
            abort(403, 'Access denied. Driver role required.');
        }

        // Get driver's assigned trips
        $assignedTrips = Trip::with(['creator', 'joins.user', 'tripDriver'])
            ->whereHas('tripDriver', function ($query) use ($driver) {
                $query->where('driver_id', $driver->id);
            })
            ->orderBy('planned_departure_time', 'asc')
            ->paginate(15);

        // Statistics for my trips only
        $stats = [
            'my_assigned' => TripDriver::where('driver_id', $driver->id)
                ->where('status', 'assigned')
                ->count(),
            'my_confirmed' => TripDriver::where('driver_id', $driver->id)
                ->where('status', 'confirmed')
                ->count(),
            'my_completed' => TripDriver::where('driver_id', $driver->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('driver.my-trips', compact('assignedTrips', 'stats'));
    }

    /**
     * Show trip details for driver
     */
    public function showTrip(Trip $trip)
    {
        $driver = Auth::user();
        
        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        $trip->load(['creator', 'joins.user', 'tripDriver']);

        // Check if driver has access to this trip
        $hasAccess = $trip->tripDriver && $trip->tripDriver->driver_id === $driver->id;
        
        if (!$hasAccess && $trip->hasDriver()) {
            abort(403, 'This trip is assigned to another driver.');
        }

        return view('driver.trip-details', compact('trip', 'isMobile'));
    }
}

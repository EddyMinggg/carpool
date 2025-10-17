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

        // Get driver's assigned trips for statistics (simplified flow)
        $myTrips = $driver->driverTrips()->with(['trip'])
            ->get()
            ->map(function ($tripDriver) {
                // Use assignment status (confirmed = active, completed = done)
                $tripDriver->trip_status = $tripDriver->trip->trip_status;
                $tripDriver->assignment_status = $tripDriver->status;
                return $tripDriver;
            });

        return view('driver.dashboard', compact('availableTrips', 'myTrips'));
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

            // Create trip-driver assignment with confirmed status (no need for separate confirmation)
            TripDriver::create([
                'trip_id' => $trip->id,
                'driver_id' => $driver->id,
                'status' => 'confirmed', // Direct confirmation when accepting
                'notes' => $request->notes,
                'assigned_at' => Carbon::now(),
                'confirmed_at' => Carbon::now(), // Set confirmed time immediately
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

        // 調試信息
        \Log::info('Cancel Trip Debug', [
            'tripDriver_id' => $tripDriver->id,
            'tripDriver_driver_id' => $tripDriver->driver_id,
            'current_driver_id' => $driver->id,
            'driver_role' => $driver->role ?? 'no_role'
        ]);

        if ($tripDriver->driver_id !== $driver->id) {
            abort(403, "Unauthorized action. TripDriver belongs to driver {$tripDriver->driver_id}, but current user is {$driver->id}");
        }

        // 檢查行程狀態：只有 awaiting 狀態才能取消，一旦 departed 就不能取消
        if ($tripDriver->trip->trip_status !== 'awaiting') {
            return back()->with('error', 'Cannot cancel assignment after trip has departed. Current status: ' . $tripDriver->trip->trip_status);
        }

        // Check if it's too close to departure time (less than 2 hours)
        $hoursUntilDeparture = Carbon::now()->diffInHours($tripDriver->trip->planned_departure_time, false);
        if ($hoursUntilDeparture < 2 && $hoursUntilDeparture > 0) {
            return back()->with('error', 'Cannot cancel trip less than 2 hours before departure.');
        }

        DB::beginTransaction();
        try {
            // 將 trip 狀態改回 awaiting，刪除司機分配記錄
            $tripDriver->trip->update(['trip_status' => 'awaiting']);
            $tripDriver->delete();

            DB::commit();
            return back()->with('success', 'Trip assignment cancelled. Trip is now available for other drivers.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to cancel trip assignment. Please try again.');
        }
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

        // 檢查 trip 狀態是否允許完成 - 只有已出發的行程才能完成
        if ($tripDriver->trip->trip_status !== 'departed') {
            return back()->with('error', 'Trip must be departed to be completed.');
        }

        DB::beginTransaction();
        try {
            // 只更新 trip 狀態為完成，TripDriver 保持 confirmed
            $tripDriver->trip->update(['trip_status' => 'completed']);

            DB::commit();
            return back()->with('success', 'Trip marked as completed!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to complete trip. Please try again.');
        }
    }

    /**
     * Mark trip as departed (司機標記已出發)
     */
    public function departTrip(TripDriver $tripDriver)
    {
        $driver = Auth::user();

        if ($tripDriver->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        // 檢查是否可以標記出發 - 只有 awaiting 狀態且已確認的行程才能出發
        if ($tripDriver->trip->trip_status !== 'awaiting' || $tripDriver->status !== 'confirmed') {
            return back()->with('error', 'Trip cannot be marked as departed. Current status: ' . $tripDriver->trip->trip_status);
        }

        DB::beginTransaction();
        try {
            // 更新行程狀態為已出發
            $tripDriver->trip->update([
                'trip_status' => 'departed',
                'actual_departure_time' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Trip marked as departed! Don\'t forget to mark as charging when you start collecting fees.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to mark trip as departed. Please try again.');
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

        // Get driver's assigned trips with proper eager loading
        $assignedTrips = Trip::with(['creator', 'joins.user', 'tripDriver'])
            ->whereHas('tripDriver', function ($query) use ($driver) {
                $query->where('driver_id', $driver->id);
            })
            ->orderBy('planned_departure_time', 'asc')
            ->paginate(15);

        // Statistics for my trips (simplified: awaiting -> departed -> completed)
        $stats = [
            'my_active' => TripDriver::where('driver_id', $driver->id)
                ->where('status', 'confirmed')
                ->whereHas('trip', function ($query) {
                    $query->whereIn('trip_status', ['awaiting', 'departed']);
                })
                ->count(),
            'my_completed' => TripDriver::where('driver_id', $driver->id)
                ->whereHas('trip', function ($query) {
                    $query->where('trip_status', 'completed');
                })
                ->count(),
            'my_cancelled' => TripDriver::where('driver_id', $driver->id)
                ->whereHas('trip', function ($query) {
                    $query->where('trip_status', 'cancelled');
                })
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

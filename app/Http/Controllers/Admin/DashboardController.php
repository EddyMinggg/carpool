<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalTrips = Trip::count();
        $pendingTrips = Trip::where('trip_status', 'pending')->count();
        $upcomingTrips = Trip::where('planned_departure_time', '>', now())
            ->withCount('joins')
            ->orderBy('planned_departure_time', 'asc')
            ->take(5)
            ->get();
        $couponUsed = \App\Models\Coupon::sum('used_count');
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTrips',
            'pendingTrips',
            'upcomingTrips',
            'couponUsed'
        ));
    }
}

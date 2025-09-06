<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
{
    $totalUsers = User::count();
    $totalTrips = Trip::count();
    $pendingTrips = Trip::where('trip_status', 'pending')->count();
    $upcomingTrips = Trip::where('plan_departure_time', '>', now())
                        ->orderBy('plan_departure_time', 'asc')
                        ->take(5)
                        ->get();

    return view('admin.dashboard', compact(
        'totalUsers', 
        'totalTrips', 
        'pendingTrips',
        'upcomingTrips'
    ));
}
}

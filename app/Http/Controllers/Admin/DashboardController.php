<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $data = [
            'totalUsers' => User::count(),
            'totalTrips' => Trip::count(),
            'pendingTrips' => Trip::where('trip_status', Trip::STATUS_PENDING)->count(),
        ];

        return view('admin.dashboard', $data);
    }
}

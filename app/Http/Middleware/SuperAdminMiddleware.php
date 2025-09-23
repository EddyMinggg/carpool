<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is a super admin
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied. Super Admin only.');
        }

        return $next($request);
    }
}
<?php

// Namespace must be exactly this (matches the folder structure)
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Class name must be exactly "AdminMiddleware" (matches the filename)
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is an admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Access denied. Admin only.');
        }

        return $next($request);
    }
}
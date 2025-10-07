<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDriverTripAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Block drivers from accessing trip ordering functionality
        if (auth()->check() && auth()->user()->isDriver()) {
            abort(403, 'Drivers cannot access trip ordering functionality.');
        }

        return $next($request);
    }
}

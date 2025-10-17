<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\OtpService;
use Auth;

class PhoneVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->hasVerifiedPhone()) {
            $res = (new OtpService(Auth::user()))->sendOtp();
            if ($res['success']) {
                return redirect()->route('verification.notice');
            }
        }

        return $next($request);
    }
}

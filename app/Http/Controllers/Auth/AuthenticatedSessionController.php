<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();

        session()->put('guest_mode', false);
        session()->save();

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isDriver()) {
            return redirect()->route('driver.dashboard');
        }

        return redirect()->route('dashboard');
    }
    public function guest(Request $request): RedirectResponse
    {
        $fullPhoneNumber = $request->input('phone_country_code_invite') . $request->input('phone_invite');
        $trip = Trip::where('invitation_code', $request->input('invitation_code'))
            ->whereNotIn('trip_status', ['completed', 'cancelled'])
            ->latest()
            ->first();

        if ($trip->joins()->where('user_phone', $fullPhoneNumber)->first()) {
            session()->put('guest_mode', true);
            session()->save();

            return redirect()->route('trips.show', ['id' => $trip->id, 'user_phone' => $fullPhoneNumber]);
        }


        return redirect()->back();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

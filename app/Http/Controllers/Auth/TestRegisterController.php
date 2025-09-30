<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TestRegisterController extends Controller
{
    /**
     * Handle test registration (bypasses OTP)
     * This is for testing email verification without AWS SNS
     */
    public function testRegister(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_country_code' => ['required', 'string', 'in:+852,+86,+1,+44'],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{8,15}$/',
                function ($attribute, $value, $fail) use ($request) {
                    $fullPhone = $request->phone_country_code . $value;
                    if (User::where('phone', $fullPhone)->exists()) {
                        $fail('The phone number has already been taken.');
                    }
                }
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'The phone number must be 8-15 digits.',
            'phone_country_code.in' => 'Please select a valid country code.',
        ]);

        // Combine country code and phone number
        $fullPhoneNumber = $request->phone_country_code . $request->phone;

        // Create user account directly (bypass OTP for testing)
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $fullPhoneNumber,
            'password' => Hash::make($request->password),
            'user_role' => User::ROLE_USER,
            'phone_verified_at' => now(), // Mark phone as verified for testing
            // Note: email_verified_at will be set when user clicks email verification link
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        return redirect(route('verification.notice'))->with(
            'success',
            'Test registration completed! Phone is auto-verified. Please check your email (or logs) to verify your email address.'
        );
    }
}

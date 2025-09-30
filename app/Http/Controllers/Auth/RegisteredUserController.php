<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_country_code' => ['required', 'string', 'in:+852,+86,+1,+44'],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{8,15}$/', // 8-15 digits for phone number
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

        // Prepare user data for temporary storage
        $userData = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $fullPhoneNumber,
            'password' => Hash::make($request->password),
            'user_role' => User::ROLE_USER,
        ];

        // Temporarily skip phone verification and create user directly
        // Create user account with phone marked as verified for now
        $user = User::create([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => $userData['password'], // Already hashed
            'user_role' => $userData['user_role'],
            'phone_verified_at' => now(), // Mark phone as verified temporarily
            // Note: email_verified_at will be set when user clicks email verification link
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('verification.notice'))->with(
            'success',
            'Registration completed! Please check your email to verify your email address.'
        );
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!Session::has('otp_phone')) {
            return redirect()->route('register')->withErrors(['general' => 'Please complete the registration form first.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify OTP and create user account
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        $phone = Session::get('otp_phone');
        if (!$phone) {
            return redirect()->route('register')->withErrors(['general' => 'Session expired. Please register again.']);
        }

        $otpService = app(OtpService::class);
        $result = $otpService->verifyOtp($phone, $request->otp_code, $request->ip());

        if (!$result['success']) {
            return back()->withErrors(['otp_code' => $result['message']]);
        }

        // Get user data from verification record
        $userData = $result['user_data'];
        if (!$userData) {
            return redirect()->route('register')->withErrors(['general' => 'Registration data not found. Please register again.']);
        }

        // Create user account
        $user = User::create([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => $userData['password'], // Already hashed
            'user_role' => $userData['user_role'],
            'phone_verified_at' => now(),
            // Note: email_verified_at will be set when user clicks email verification link
        ]);

        // Clean up session
        Session::forget('otp_phone');
        Session::forget('otp_user_data');

        event(new Registered($user));
        Auth::login($user);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        return redirect(route('verification.notice'))->with(
            'success',
            'Registration completed! Your phone is verified. Please check your email to verify your email address.'
        );
    }

    /**
     * Resend OTP code (JSON API for AJAX requests)
     */
    public function resendOtp(Request $request)
    {
        $phone = Session::get('otp_phone');
        $userData = Session::get('otp_user_data');

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please register again.'
            ], 400);
        }

        $otpService = app(OtpService::class);
        $result = $otpService->sendOtp($phone, $userData, $request->ip());

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }

    /**
     * Resend OTP code (for form submissions)
     */
    public function resendOtpForm(Request $request): RedirectResponse
    {
        $phone = Session::get('otp_phone');
        $userData = Session::get('otp_user_data');

        if (!$phone) {
            return redirect()->route('register')->withErrors(['general' => 'Session expired. Please register again.']);
        }

        $otpService = app(OtpService::class);
        $result = $otpService->sendOtp($phone, $userData, $request->ip());

        if (!$result['success']) {
            return back()->withErrors(['general' => $result['message']]);
        }

        return back()->with('success', 'A new verification code has been sent to your phone.');
    }
}

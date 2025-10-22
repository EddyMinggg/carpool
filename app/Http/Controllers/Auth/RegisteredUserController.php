<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use Carbon\Carbon;

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
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z]).+$/', // At least one uppercase and one lowercase letter
            ],
        ], [
            'phone.regex' => 'The phone number must be 8-15 digits.',
            'phone_country_code.in' => 'Please select a valid country code.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter and one lowercase letter.',
        ]);

        // Combine country code and phone number
        $fullPhoneNumber = $request->phone_country_code . $request->phone;

        // Check if this phone number has been verified before (as a guest)
        $existingVerification = \App\Models\PhoneVerification::where('phone', $fullPhoneNumber)
            ->where('is_verified', true)
            ->latest()
            ->first();

        // Prepare user data
        $userData = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $fullPhoneNumber,
            'password' => Hash::make($request->password),
            'user_role' => User::ROLE_USER,
        ];

        // If phone was already verified as guest, create user with phone_verified_at set
        if ($existingVerification) {
            $user = User::create([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => $userData['password'],
                'user_role' => $userData['user_role'],
                'phone_verified_at' => $existingVerification->updated_at ?? Carbon::now(),
            ]);

            Auth::login($user);

            return redirect(route('dashboard'))->with(
                'success',
                'Registration completed! Your phone number was already verified.'
            );
        }

        // Phone not verified before, send OTP
        $tempUser = new User([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => $userData['password'],
            'user_role' => $userData['user_role'],
            'notification_channel' => 'whatsapp',
        ]);

        $res = (new OtpService($tempUser))->sendOtp();

        if ($res['success']) {
            $user = User::create([
                'username' => $userData['username'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => $userData['password'],
                'user_role' => $userData['user_role'],
            ]);

            Auth::login($user);

            return redirect(route('verification.notice'))->with(
                'success',
                'Registration completed! Please check your SMS to verify your phone number.'
            );
        }

        return redirect()->back()->withInput()->withErrors([
            'phone' => 'Failed to send OTP. Please check your phone number and try again.'
        ]);
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    /**
     * Verify OTP and create user account
     */
    public function verifyOtp(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        $res = (new OtpService($user))->verifyOtp($request->input('otp_code'));

        return $res;
    }

    /**
     * Resend OTP code (JSON API for AJAX requests)
     */
    public function resendOtp(Request $request)
    {
        $user = $request->user();
        $res = (new OtpService($user))->sendOtp();

        return response()->json([
            'success' => $res['success'],
            'message' => $res['message']
        ]);
    }
}

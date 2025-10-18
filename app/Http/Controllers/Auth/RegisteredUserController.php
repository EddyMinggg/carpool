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

        $tempUser = new User([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => $userData['password'],
            'user_role' => $userData['user_role'],
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

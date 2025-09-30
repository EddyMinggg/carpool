<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Exception;

class SimpleOtpController extends Controller
{
    /**
     * Handle registration with simple OTP (without AWS for now)
     */
    public function simpleRegister(Request $request): RedirectResponse
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

        // Generate simple OTP code (for testing)
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(5);

        // Prepare user data for temporary storage
        $userData = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $fullPhoneNumber,
            'password' => Hash::make($request->password),
            'user_role' => User::ROLE_USER,
        ];

        // Clean up old OTP records for this phone
        DB::table('phone_verifications')
            ->where('phone', $fullPhoneNumber)
            ->delete();

        // Store OTP in database
        DB::table('phone_verifications')->insert([
            'phone' => $fullPhoneNumber,
            'otp_code' => $otpCode,
            'expires_at' => $expiresAt,
            'ip_address' => $request->ip(),
            'user_data' => json_encode($userData),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Log the OTP code for testing (instead of sending SMS)
        Log::info('OTP Code Generated', [
            'phone' => $fullPhoneNumber,
            'otp_code' => $otpCode,
            'expires_at' => $expiresAt
        ]);

        // Store phone number in session for OTP verification page
        Session::put('otp_phone', $fullPhoneNumber);

        return redirect()->route('otp.verify')->with(
            'success',
            'A verification code has been generated. Check the logs for the code: ' . $otpCode
        );
    }
}

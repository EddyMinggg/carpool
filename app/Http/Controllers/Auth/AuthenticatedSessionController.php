<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Trip;
use App\Models\User;
use App\Services\OtpService;
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

        // Check if user is active
        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact administrator for assistance.',
            ])->onlyInput('email');
        }

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
        // 验证输入
        $request->validate([
            'invitation_code' => 'required|string',
            'phone_invite' => 'required|string',
            'phone_country_code_invite' => 'required|string',
        ]);

        $fullPhoneNumber = $request->input('phone_country_code_invite') . $request->input('phone_invite');
        
        // 查找行程
        $trip = Trip::where('invitation_code', $request->input('invitation_code'))
            ->whereNotIn('trip_status', ['completed', 'cancelled'])
            ->latest()
            ->first();

        // 检查行程是否存在
        if (!$trip) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'invitation_code' => __('Invalid invitation code. Please check and try again.')
                ]);
        }

        // 检查用户是否已加入此行程
        $userJoin = $trip->joins()->where('user_phone', $fullPhoneNumber)->first();
        
        if (!$userJoin) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'phone_invite' => __('This phone number is not registered for this trip. Please check the phone number or use the correct invitation code.')
                ]);
        }

        // 检查电话号码是否已验证（查找已验证的记录）
        $isPhoneVerified = \DB::table('phone_verifications')
            ->where('phone', $fullPhoneNumber)
            ->where('is_verified', true)
            ->exists();

        // 如果是第一次访问（未验证），需要发送 OTP
        if (!$isPhoneVerified) {
            // 创建临时用户对象用于发送 OTP
            $tempUser = new User([
                'phone' => $fullPhoneNumber,
            ]);

            $res = (new \App\Services\OtpService($tempUser))->sendOtp();

            if ($res['success']) {
                // 将必要信息存入 session
                session([
                    'guest_verification' => [
                        'phone' => $fullPhoneNumber,
                        'trip_id' => $trip->id,
                        'invitation_code' => $request->input('invitation_code'),
                    ]
                ]);

                return redirect()->route('guest.verify.otp')->with(
                    'success',
                    'Please check your SMS to verify your phone number.'
                );
            }

            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'phone_invite' => 'Failed to send OTP. Please check your phone number and try again.'
                ]);
        }

        // 如果已验证过，直接设置访客模式并重定向到行程页面
        session()->put('guest_mode', true);
        session()->save();

        return redirect()->route('trips.show', ['id' => $trip->id, 'user_phone' => $fullPhoneNumber]);
    }

    /**
     * Show Guest OTP verification form
     */
    public function showGuestOtpForm()
    {
        // 检查 session 中是否有待验证的信息
        if (!session()->has('guest_verification')) {
            return redirect()->route('login')->withErrors([
                'phone_invite' => 'Verification session expired. Please try again.'
            ]);
        }

        return view('auth.verify-guest-otp');
    }

    /**
     * Verify Guest OTP
     */
    public function verifyGuestOtp(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        // 从 session 获取验证信息
        $guestData = session('guest_verification');
        
        if (!$guestData) {
            return response()->json([
                'success' => false,
                'message' => 'Verification session expired. Please try again.'
            ]);
        }

        try {
            $verification = \App\Models\PhoneVerification::where('phone', $guestData['phone'])
                ->where('is_verified', false)
                ->latest()
                ->first();

            if (!$verification) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending verification found for this phone number.'
                ]);
            }

            // Check if OTP has expired
            if (\Carbon\Carbon::parse($verification->expires_at)->isPast()) {
                \App\Models\PhoneVerification::where('id', $verification->id)->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'OTP code has expired. Please request a new one.'
                ]);
            }

            // Check max attempts
            if ($verification->attempts >= 3) {
                \App\Models\PhoneVerification::where('id', $verification->id)->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Too many failed attempts. Please request a new OTP code.'
                ]);
            }

            // Verify OTP code
            if ($verification->otp_code != $request->input('otp_code')) {
                // Increment attempts
                \App\Models\PhoneVerification::where('id', $verification->id)->increment('attempts');

                $remainingAttempts = 3 - ($verification->attempts + 1);

                return response()->json([
                    'success' => false,
                    'message' => "Invalid OTP code. You have {$remainingAttempts} attempts remaining."
                ]);
            }

            // Mark as verified (for guest, we only update phone_verifications table)
            \App\Models\PhoneVerification::where('id', $verification->id)
                ->update([
                    'is_verified' => true,
                    'updated_at' => \Carbon\Carbon::now()
                ]);

            // 验证成功，设置访客模式并清除验证 session
            session()->forget('guest_verification');
            session()->put('guest_mode', true);
            session()->save();

            return response()->json([
                'success' => true,
                'message' => 'Phone number verified successfully.',
                'redirect_url' => route('trips.show', [
                    'id' => $guestData['trip_id'],
                    'user_phone' => $guestData['phone']
                ])
            ]);

        } catch (\Exception $e) {
            \Log::error('Guest OTP verification error', [
                'phone' => $guestData['phone'],
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ]);
        }
    }

    /**
     * Resend Guest OTP code
     */
    public function resendGuestOtp(Request $request)
    {
        $guestData = session('guest_verification');
        
        if (!$guestData) {
            return response()->json([
                'success' => false,
                'message' => 'Verification session expired. Please try again.'
            ]);
        }

        $tempUser = new User([
            'phone' => $guestData['phone'],
        ]);

        $res = (new OtpService($tempUser))->sendOtp();

        return response()->json([
            'success' => $res['success'],
            'message' => $res['message']
        ]);
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

<?php

namespace App\Services;

use App\Models\PhoneVerification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

use App\Notifications\OtpNotification;

class OtpService
{
    private $maxAttempts = 3;
    private $otpExpireMinutes = 5;
    private $resendCooldownMinutes = 0.5; // 10 seconds for development

    private $isSandbox;

    public function __construct(readonly private User $user)
    {
        $this->isSandbox = config('sms.mode') === 'SANDBOX';
    }

    /**
     * Generate and send OTP code
     */
    public function sendOtp()
    {
        try {
            // Check rate limiting - only allow one OTP per minute
            $recentOtp = PhoneVerification::where('phone', $this->user->phone)
                ->where('created_at', '>', Carbon::now()->subMinutes($this->resendCooldownMinutes))
                ->first();

            if ($recentOtp) {
                return [
                    'success' => false,
                    'message' => 'Please wait before requesting another OTP code.',
                    'retry_after' => $this->resendCooldownMinutes * 60
                ];
            }

            // Generate 6-digit OTP
            $otpCode = $this->isSandbox ? '000000' : str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes($this->otpExpireMinutes);

            // Clean up old OTP records for this phone
            PhoneVerification::where('phone', $this->user->phone)->delete();

            // Store OTP in database
            PhoneVerification::create([
                'phone' => $this->user->phone,
                'otp_code' => $otpCode,
                'expires_at' => $expiresAt,
                'ip_address' => request()->ip()
            ]);

            if (!$this->isSandbox) {
                $this->user->notify(new OtpNotification($otpCode));
            }

            // // Send SMS via Vonage using professional template
            // $message = SmsTemplateService::otpVerification($otpCode, $this->otpExpireMinutes);

            // $smsResult = $this->vonageSmsService->sendSms($phone, $message);

            // if (!$smsResult['success']) {
            //     Log::error('Failed to send OTP via Vonage', [
            //         'phone' => $phone,
            //         'error' => $smsResult['error'] ?? 'Unknown error'
            //     ]);

            //     return [
            //         'success' => false,
            //         'message' => 'Failed to send OTP code. Please try again.',
            //         'error' => $smsResult['error'] ?? 'SMS service error'
            //     ];
            // }

            // Log::info('OTP sent successfully via Vonage', [
            //     'phone' => $phone,
            //     'message_id' => $smsResult['message_id'] ?? null,
            //     'otp_code' => $otpCode // Log OTP for development
            // ]);

            // // In development mode, also write OTP to a file for easy access
            // if (app()->environment('local')) {
            //     $otpFile = storage_path('logs/current_otp.txt');
            //     file_put_contents($otpFile, "Phone: {$phone}\nOTP Code: {$otpCode}\nTime: " . now()->format('Y-m-d H:i:s') . "\n");
            // }

            return [
                'success' => true,
                'message' => 'OTP code sent successfully.',
                'expires_in' => $this->otpExpireMinutes * 60,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send OTP code. Please try again.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $otpCode): array
    {
        try {
            $verification = PhoneVerification::where('phone', $this->user->phone)
                ->where('is_verified', false)
                ->latest()
                ->first();

            if (!$verification) {
                return [
                    'success' => false,
                    'message' => 'No pending verification found for this phone number.'
                ];
            }

            // Check if OTP has expired
            if (Carbon::parse($verification->expires_at)->isPast()) {
                PhoneVerification::where('id', $verification->id)->delete();

                return [
                    'success' => false,
                    'message' => 'OTP code has expired. Please request a new one.'
                ];
            }

            // Check max attempts
            if ($verification->attempts >= $this->maxAttempts) {
                PhoneVerification::where('id', $verification->id)->delete();

                return [
                    'success' => false,
                    'message' => 'Too many failed attempts. Please request a new OTP code.'
                ];
            }

            // Verify OTP code
            if ($verification->otp_code != $otpCode) {
                // Increment attempts
                PhoneVerification::where('id', $verification->id)->increment('attempts');

                $remainingAttempts = $this->maxAttempts - ($verification->attempts + 1);

                return [
                    'success' => false,
                    'message' => "Invalid OTP code. You have {$remainingAttempts} attempts remaining."
                ];
            }

            // Mark as verified
            PhoneVerification::where('id', $verification->id)
                ->update([
                    'is_verified' => true,
                    'updated_at' => Carbon::now()
                ]);

            $this->user->markPhoneAsVerified();

            return [
                'success' => true,
                'message' => 'Phone number verified successfully.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ];
        }
    }

    /**
     * Clean up expired OTP records
     */
    public function cleanupExpiredOtps(): int
    {
        return PhoneVerification::where('expires_at', '<', Carbon::now())->delete();
    }

    /**
     * Get OTP status for phone number
     */
    public function getOtpStatus(string $phone): array
    {
        $verification = PhoneVerification::where('phone', $phone)
            ->where('is_verified', false)
            ->first();

        if (!$verification) {
            return ['status' => 'none'];
        }

        if (Carbon::parse($verification->expires_at)->isPast()) {
            return ['status' => 'expired'];
        }

        $timeLeft = Carbon::parse($verification->expires_at)->diffInSeconds(Carbon::now());

        return [
            'status' => 'pending',
            'expires_in' => $timeLeft,
            'attempts' => $verification->attempts,
            'max_attempts' => $this->maxAttempts
        ];
    }
}

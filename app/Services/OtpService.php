<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use GuzzleHttp\Client as HttpClient;

class OtpService
{
    private $vonageClient;
    private $maxAttempts = 3;
    private $otpExpireMinutes = 5;
    private $resendCooldownMinutes = 0.17; // 10 seconds for development

    public function __construct()
    {
        $credentials = new Basic(
            config('services.vonage.api_key'),
            config('services.vonage.api_secret')
        );
        
        // Configure HTTP client for local development SSL issues
        $httpClient = null;
        if (app()->environment('local')) {
            $httpClient = new HttpClient([
                'verify' => false, // Disable SSL verification for local development
                'timeout' => 30,
            ]);
        }
        
        $this->vonageClient = new Client($credentials, [], $httpClient);
    }

    /**
     * Generate and send OTP code
     */
    public function sendOtp(string $phone, array $userData = null, string $ipAddress = null): array
    {
        try {
            // Check rate limiting - only allow one OTP per minute
            $recentOtp = DB::table('phone_verifications')
                ->where('phone', $phone)
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
            $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes($this->otpExpireMinutes);

            // Clean up old OTP records for this phone
            DB::table('phone_verifications')
                ->where('phone', $phone)
                ->delete();

            // Store OTP in database
            DB::table('phone_verifications')->insert([
                'phone' => $phone,
                'otp_code' => $otpCode,
                'expires_at' => $expiresAt,
                'ip_address' => $ipAddress,
                'user_data' => $userData ? json_encode($userData) : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Send SMS via Vonage using professional template
            $message = SmsTemplateService::otpVerification($otpCode, $this->otpExpireMinutes);

            try {
                $sms = new SMS($phone, config('services.vonage.from_number'), $message);
                $response = $this->vonageClient->sms()->send($sms);
                
                $smsMessage = $response->current();
                
                if ($smsMessage->getStatus() == 0) {
                    Log::info('OTP SMS sent successfully via Vonage', [
                        'phone' => $phone,
                        'message_id' => $smsMessage->getMessageId()
                    ]);
                } else {
                    Log::error('Failed to send OTP via Vonage', [
                        'phone' => $phone,
                        'status' => $smsMessage->getStatus()
                    ]);

                    return [
                        'success' => false,
                        'message' => 'Failed to send OTP code. Please try again.',
                        'error' => 'SMS delivery failed'
                    ];
                }
            } catch (Exception $e) {
                Log::error('Vonage SMS API error', [
                    'phone' => $phone,
                    'error' => $e->getMessage()
                ]);

                return [
                    'success' => false,
                    'message' => 'SMS service temporarily unavailable. Please try again.',
                    'error' => $e->getMessage()
                ];
            }

            Log::info('OTP sent successfully via Vonage', [
                'phone' => $phone,
                'message_id' => $smsResult['message_id'] ?? null,
                'otp_code' => $otpCode // Log OTP for development
            ]);

            // In development mode, also write OTP to a file for easy access
            if (app()->environment('local')) {
                $otpFile = storage_path('logs/current_otp.txt');
                file_put_contents($otpFile, "Phone: {$phone}\nOTP Code: {$otpCode}\nTime: " . now()->format('Y-m-d H:i:s') . "\n");
            }

            return [
                'success' => true,
                'message' => 'OTP code sent successfully.',
                'expires_in' => $this->otpExpireMinutes * 60,
                'message_id' => $smsResult['message_id'] ?? null
            ];

        } catch (Exception $e) {
            Log::error('OTP Service Error', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP code. Please try again.',
                'error' => $e->getMessage()
            ];

        } catch (Exception $e) {
            Log::error('OTP Service Error', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $phone, string $otpCode, string $ipAddress = null): array
    {
        try {
            $verification = DB::table('phone_verifications')
                ->where('phone', $phone)
                ->where('is_verified', false)
                ->first();

            if (!$verification) {
                return [
                    'success' => false,
                    'message' => 'No pending verification found for this phone number.'
                ];
            }

            // Check if OTP has expired
            if (Carbon::parse($verification->expires_at)->isPast()) {
                DB::table('phone_verifications')
                    ->where('id', $verification->id)
                    ->delete();

                return [
                    'success' => false,
                    'message' => 'OTP code has expired. Please request a new one.'
                ];
            }

            // Check max attempts
            if ($verification->attempts >= $this->maxAttempts) {
                DB::table('phone_verifications')
                    ->where('id', $verification->id)
                    ->delete();

                return [
                    'success' => false,
                    'message' => 'Too many failed attempts. Please request a new OTP code.'
                ];
            }

            // Verify OTP code
            if ($verification->otp_code !== $otpCode) {
                // Increment attempts
                DB::table('phone_verifications')
                    ->where('id', $verification->id)
                    ->increment('attempts');

                $remainingAttempts = $this->maxAttempts - ($verification->attempts + 1);

                return [
                    'success' => false,
                    'message' => "Invalid OTP code. You have {$remainingAttempts} attempts remaining."
                ];
            }

            // Mark as verified
            DB::table('phone_verifications')
                ->where('id', $verification->id)
                ->update([
                    'is_verified' => true,
                    'updated_at' => Carbon::now()
                ]);

            return [
                'success' => true,
                'message' => 'Phone number verified successfully.',
                'user_data' => $verification->user_data ? json_decode($verification->user_data, true) : null
            ];

        } catch (Exception $e) {
            Log::error('OTP Verification Error', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

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
        return DB::table('phone_verifications')
            ->where('expires_at', '<', Carbon::now())
            ->delete();
    }

    /**
     * Get OTP status for phone number
     */
    public function getOtpStatus(string $phone): array
    {
        $verification = DB::table('phone_verifications')
            ->where('phone', $phone)
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
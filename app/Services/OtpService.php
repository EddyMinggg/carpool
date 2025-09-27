<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class OtpService
{
    private $snsClient;
    private $maxAttempts = 3;
    private $otpExpireMinutes = 5;
    private $resendCooldownMinutes = 0.17; // 10 seconds for development

    public function __construct()
    {
        // Delay SNS client initialization until needed
    }

    /**
     * Get or create SNS client
     */
    private function getSnsClient()
    {
        if (!$this->snsClient) {
            $this->snsClient = new SnsClient([
                'version' => 'latest',
                'region' => config('services.aws.region', 'us-east-1'),
                'credentials' => [
                    'key' => config('services.aws.key'),
                    'secret' => config('services.aws.secret'),
                ],
                'http' => [
                    'verify' => app()->environment('production'), // Only verify SSL in production
                ],
            ]);
        }
        return $this->snsClient;
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

            // Send SMS via AWS SNS using professional template
            $message = SmsTemplateService::otpVerification($otpCode, $this->otpExpireMinutes);

            $snsClient = $this->getSnsClient();
            $result = $snsClient->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
            ]);

            Log::info('OTP sent successfully', [
                'phone' => $phone,
                'message_id' => $result['MessageId'] ?? null,
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
                'expires_in' => $this->otpExpireMinutes * 60
            ];

        } catch (AwsException $e) {
            Log::error('AWS SNS Error', [
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
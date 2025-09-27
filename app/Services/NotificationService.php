<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class NotificationService
{
    private $snsClient;
    private $otpService;

    public function __construct(OtpService $otpService = null)
    {
        $this->otpService = $otpService ?: new OtpService();
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
                    'verify' => app()->environment('production'),
                ],
            ]);
        }
        return $this->snsClient;
    }

    /**
     * Send SMS using template
     */
    public function sendSms(string $phone, string $message, array $context = []): array
    {
        try {
            // Validate SMS length
            $validation = SmsTemplateService::validateSmsLength($message);
            
            if (!$validation['is_single']) {
                Log::warning('Multi-part SMS detected', [
                    'phone' => $phone,
                    'length' => $validation['length'],
                    'parts' => $validation['parts']
                ]);
            }

            $snsClient = $this->getSnsClient();
            $result = $snsClient->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
            ]);

            Log::info('SMS sent successfully', [
                'phone' => $phone,
                'message_id' => $result['MessageId'] ?? null,
                'context' => $context,
                'length' => $validation['length']
            ]);

            return [
                'success' => true,
                'message_id' => $result['MessageId'] ?? null,
                'sms_parts' => $validation['parts']
            ];

        } catch (AwsException $e) {
            Log::error('AWS SNS SMS Error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'context' => $context
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];

        } catch (Exception $e) {
            Log::error('SMS Service Error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'context' => $context
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send welcome SMS to new user
     */
    public function sendWelcomeSms(User $user): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'User has no phone number'];
        }

        $message = SmsTemplateService::welcome($user->name);
        
        return $this->sendSms($user->phone, $message, [
            'type' => 'welcome',
            'user_id' => $user->id
        ]);
    }

    /**
     * Send trip confirmation SMS
     */
    public function sendTripConfirmation(User $user, string $tripId, string $destination, string $datetime): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'User has no phone number'];
        }

        $message = SmsTemplateService::tripConfirmation($tripId, $destination, $datetime);
        
        return $this->sendSms($user->phone, $message, [
            'type' => 'trip_confirmation',
            'user_id' => $user->id,
            'trip_id' => $tripId
        ]);
    }

    /**
     * Send driver assignment notification
     */
    public function sendDriverAssignment(User $user, string $tripId, string $driverName, string $carModel, string $plateNumber, string $driverPhone): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'User has no phone number'];
        }

        $message = SmsTemplateService::driverAssigned($tripId, $driverName, $carModel, $plateNumber, $driverPhone);
        
        return $this->sendSms($user->phone, $message, [
            'type' => 'driver_assignment',
            'user_id' => $user->id,
            'trip_id' => $tripId
        ]);
    }

    /**
     * Send trip reminder SMS
     */
    public function sendTripReminder(User $user, string $tripId, string $destination, string $pickupTime, string $pickupLocation): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'User has no phone number'];
        }

        $message = SmsTemplateService::tripReminder($tripId, $destination, $pickupTime, $pickupLocation);
        
        return $this->sendSms($user->phone, $message, [
            'type' => 'trip_reminder',
            'user_id' => $user->id,
            'trip_id' => $tripId
        ]);
    }

    /**
     * Send payment confirmation SMS
     */
    public function sendPaymentConfirmation(User $user, string $tripId, float $amount): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'User has no phone number'];
        }

        $message = SmsTemplateService::paymentConfirmation($tripId, $amount);
        
        return $this->sendSms($user->phone, $message, [
            'type' => 'payment_confirmation',
            'user_id' => $user->id,
            'trip_id' => $tripId,
            'amount' => $amount
        ]);
    }

    /**
     * Send trip cancellation SMS
     */
    public function sendTripCancellation(User $user, string $tripId, string $reason = null): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'User has no phone number'];
        }

        $message = SmsTemplateService::tripCancellation($tripId, $reason);
        
        return $this->sendSms($user->phone, $message, [
            'type' => 'trip_cancellation',
            'user_id' => $user->id,
            'trip_id' => $tripId,
            'reason' => $reason
        ]);
    }

    /**
     * Send emergency alert SMS
     */
    public function sendEmergencyAlert(User $user, string $tripId): array
    {
        if (!$user->phone) {
            return ['success' => false, 'error' => 'User has no phone number'];
        }

        $message = SmsTemplateService::emergencyAlert($tripId);
        
        return $this->sendSms($user->phone, $message, [
            'type' => 'emergency_alert',
            'user_id' => $user->id,
            'trip_id' => $tripId
        ]);
    }

    /**
     * Send bulk SMS to multiple users
     */
    public function sendBulkSms(array $users, string $templateType, array $templateData = []): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        foreach ($users as $user) {
            if (!$user->phone) {
                $results['failed']++;
                $results['details'][] = [
                    'user_id' => $user->id,
                    'success' => false,
                    'error' => 'No phone number'
                ];
                continue;
            }

            $message = SmsTemplateService::getTemplate($templateType, $templateData, $user->language ?? 'en');
            $result = $this->sendSms($user->phone, $message, [
                'type' => 'bulk_' . $templateType,
                'user_id' => $user->id
            ]);

            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
            }

            $results['details'][] = [
                'user_id' => $user->id,
                'success' => $result['success'],
                'error' => $result['error'] ?? null
            ];
        }

        return $results;
    }

    /**
     * Send OTP SMS (delegate to OtpService)
     */
    public function sendOtpSms(string $phone, array $userData = null, string $ipAddress = null): array
    {
        return $this->otpService->sendOtp($phone, $userData, $ipAddress);
    }

    /**
     * Get SMS statistics for monitoring
     */
    public function getSmsStats(int $days = 7): array
    {
        // This would typically query a SMS logs table
        // For now, return basic stats from Laravel logs
        $logPath = storage_path('logs/laravel.log');
        
        if (!file_exists($logPath)) {
            return [
                'total_sent' => 0,
                'success_rate' => 0,
                'period_days' => $days
            ];
        }

        $logs = file_get_contents($logPath);
        $successCount = substr_count($logs, 'SMS sent successfully');
        $errorCount = substr_count($logs, 'SMS Error');
        $total = $successCount + $errorCount;
        
        return [
            'total_sent' => $total,
            'successful' => $successCount,
            'failed' => $errorCount,
            'success_rate' => $total > 0 ? round(($successCount / $total) * 100, 2) : 0,
            'period_days' => $days
        ];
    }

    /**
     * Test SMS service connectivity
     */
    public function testConnection(): array
    {
        try {
            $snsClient = $this->getSnsClient();
            
            // Test with AWS SNS list subscriptions (doesn't send SMS)
            $result = $snsClient->listSubscriptions(['MaxItems' => 1]);
            
            return [
                'success' => true,
                'message' => 'SMS service connection successful',
                'service' => 'AWS SNS'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'SMS service connection failed',
                'error' => $e->getMessage()
            ];
        }
    }
}
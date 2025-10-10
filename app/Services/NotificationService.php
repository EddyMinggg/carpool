<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Trip;
use Exception;

class NotificationService
{
    private $snsClient;
    private $otpService;

    public function __construct()
    {
        // OtpService will be injected when needed
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
     * Send SMS using AWS SNS
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
     * Send team join notification to existing team members
     */
    public function sendTeamJoinNotification(Trip $trip, User $newMember, array $existingMembers): array
    {
        if (empty($existingMembers)) {
            return [
                'success' => true,
                'message' => 'No existing members to notify',
                'sent' => 0,
                'details' => []
            ];
        }

        // Get current team count (including the new member)
        $currentCount = count($existingMembers) + 1;
        $maxPeople = $trip->max_people;
        $destination = $trip->dropoff_location;
        $newMemberPhone = $this->maskPhoneNumber($newMember->phone);

        // Calculate pricing based on trip type
        $basePrice = number_format((float) $trip->price_per_person, 0);
        $discountPrice = null;

        if (!$trip->isGoldenHour() && $currentCount >= 4 && $trip->four_person_discount > 0) {
            $effectivePrice = $trip->getEffectivePricePerPerson($currentCount);
            $discountPrice = number_format($effectivePrice, 0);
        }

        // Generate appropriate message based on trip type
        if ($trip->isGoldenHour()) {
            $message = SmsTemplateService::teamJoinGoldenHour(
                $newMemberPhone,
                $currentCount,
                $maxPeople,
                $destination,
                $basePrice
            );
        } else {
            $message = SmsTemplateService::teamJoinRegularHour(
                $newMemberPhone,
                $currentCount,
                $maxPeople,
                $destination,
                $basePrice,
                $discountPrice
            );
        }

        // Send to all existing team members
        $results = [
            'success' => 0,
            'failed' => 0,
            'sent' => 0,
            'details' => []
        ];

        foreach ($existingMembers as $member) {
            // Handle case where member might be an array (from test data)
            if (is_array($member)) {
                $member = (object) $member;
            }
            
            if (!isset($member->phone) || !$member->phone) {
                $results['failed']++;
                $results['details'][] = [
                    'user_id' => $member->id ?? null,
                    'phone' => null,
                    'success' => false,
                    'error' => 'No phone number'
                ];
                continue;
            }

            $result = $this->sendSms($member->phone, $message, [
                'type' => 'team_join_notification',
                'user_id' => $member->id,
                'trip_id' => $trip->id,
                'new_member_id' => $newMember->id,
                'team_count' => $currentCount
            ]);

            if ($result['success']) {
                $results['success']++;
                $results['sent']++;
            } else {
                $results['failed']++;
            }

            $results['details'][] = [
                'user_id' => $member->id,
                'phone' => $this->maskPhoneNumber($member->phone),
                'success' => $result['success'],
                'error' => $result['error'] ?? null,
                'message_id' => $result['message_id'] ?? null
            ];
        }

        Log::info('Team join notifications sent', [
            'trip_id' => $trip->id,
            'new_member_id' => $newMember->id,
            'team_count' => $currentCount,
            'sent_count' => $results['sent'],
            'failed_count' => $results['failed']
        ]);

        return $results;
    }

    /**
     * Send team full notification
     */
    public function sendTeamFullNotification(Trip $trip, array $allMembers): array
    {
        $teamCount = count($allMembers);
        $destination = $trip->dropoff_location;
        $finalPrice = number_format($trip->getEffectivePricePerPerson($teamCount), 0);

        $message = SmsTemplateService::teamFull(
            $destination,
            $teamCount,
            $finalPrice
        );

        return $this->sendBulkSms($allMembers, 'team_full', [
            'trip_id' => $trip->id,
            'message' => $message
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

            // Use custom message if provided, otherwise use template
            $message = $templateData['message'] ?? SmsTemplateService::getTemplate($templateType, $templateData, $user->language ?? 'en');
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
                'phone' => $this->maskPhoneNumber($user->phone),
                'success' => $result['success'],
                'error' => $result['error'] ?? null,
                'message_id' => $result['message_id'] ?? null
            ];
        }

        return $results;
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
                'successful' => 0,
                'failed' => 0,
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

    /**
     * Mask phone number for privacy (show last 4 digits)
     */
    private function maskPhoneNumber(string $phone): string
    {
        if (strlen($phone) <= 4) {
            return $phone;
        }
        return str_repeat('*', strlen($phone) - 4) . substr($phone, -4);
    }
}
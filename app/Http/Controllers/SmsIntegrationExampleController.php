<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Services\SmsTemplateService;
use App\Models\User;
use App\Models\Trip;

class SmsIntegrationExampleController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Example: Send welcome SMS to new user
     */
    public function sendWelcomeSms($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $result = $this->notificationService->sendWelcomeSms($user);

        if ($result['success']) {
            return response()->json([
                'message' => 'Welcome SMS sent successfully',
                'sms_parts' => $result['sms_parts']
            ]);
        }

        return response()->json([
            'error' => 'Failed to send SMS',
            'details' => $result['error']
        ], 500);
    }

    /**
     * Example: Send trip confirmation SMS
     */
    public function sendTripConfirmation($tripId)
    {
        $trip = Trip::find($tripId);
        
        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $user = $trip->user;
        
        $result = $this->notificationService->sendTripConfirmation(
            $user,
            $trip->id,
            $trip->destination,
            $trip->departure_time->format('Y-m-d H:i')
        );

        if ($result['success']) {
            return response()->json([
                'message' => 'Trip confirmation SMS sent successfully',
                'message_id' => $result['message_id']
            ]);
        }

        return response()->json([
            'error' => 'Failed to send SMS',
            'details' => $result['error']
        ], 500);
    }

    /**
     * Example: Send bulk SMS to all trip members
     */
    public function sendBulkTripReminder($tripId)
    {
        $trip = Trip::with('members')->find($tripId);
        
        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $users = $trip->members;
        
        $result = $this->notificationService->sendBulkSms($users, 'trip_reminder_1h', [
            'trip_id' => $trip->id,
            'destination' => $trip->destination,
            'pickup_time' => $trip->departure_time->format('H:i'),
            'pickup_location' => $trip->pickup_location
        ]);

        return response()->json([
            'message' => 'Bulk SMS sent',
            'successful' => $result['success'],
            'failed' => $result['failed'],
            'total' => count($users),
            'details' => $result['details']
        ]);
    }

    /**
     * Example: Send emergency alert
     */
    public function sendEmergencyAlert($tripId)
    {
        $trip = Trip::with('members')->find($tripId);
        
        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $results = [];
        
        foreach ($trip->members as $user) {
            $result = $this->notificationService->sendEmergencyAlert($user, $trip->id);
            $results[] = [
                'user_id' => $user->id,
                'success' => $result['success'],
                'error' => $result['error'] ?? null
            ];
        }

        return response()->json([
            'message' => 'Emergency alerts sent',
            'results' => $results
        ]);
    }

    /**
     * Example: Get SMS statistics
     */
    public function getSmsStats()
    {
        $stats = $this->notificationService->getSmsStats(30); // Last 30 days
        
        return response()->json($stats);
    }

    /**
     * Example: Test SMS service connectivity
     */
    public function testSmsService()
    {
        $result = $this->notificationService->testConnection();
        
        return response()->json($result);
    }

    /**
     * Example: Preview SMS templates without sending
     */
    public function previewSmsTemplates()
    {
        $previews = [
            'otp' => SmsTemplateService::otpVerification('123456', 5),
            'welcome' => SmsTemplateService::welcome('John Doe'),
            'trip_confirmation' => SmsTemplateService::tripConfirmation('T12345', 'Airport', '2025-09-27 15:30'),
            'driver_assigned' => SmsTemplateService::driverAssigned('T12345', 'Wong Tai Man', 'Toyota Camry', 'ABC123', '+852 9876 5432'),
            'payment_confirmed' => SmsTemplateService::paymentConfirmation('T12345', 150.00),
            'emergency' => SmsTemplateService::emergencyAlert('T12345'),
        ];

        $statistics = [];
        foreach ($previews as $type => $message) {
            $validation = SmsTemplateService::validateSmsLength($message);
            $statistics[$type] = [
                'message' => $message,
                'length' => $validation['length'],
                'parts' => $validation['parts'],
                'is_single' => $validation['is_single']
            ];
        }

        return response()->json([
            'templates' => $statistics,
            'summary' => [
                'total_templates' => count($statistics),
                'single_part_templates' => array_sum(array_column($statistics, 'is_single')),
                'multi_part_templates' => count($statistics) - array_sum(array_column($statistics, 'is_single'))
            ]
        ]);
    }

    /**
     * Example: Cost estimation for SMS campaign
     */
    public function estimateSmsCost(Request $request)
    {
        $messages = $request->input('messages', []);
        $costPerSms = $request->input('cost_per_sms', 0.05);

        if (empty($messages)) {
            // Example messages for demo
            $messages = [
                SmsTemplateService::welcome('User 1'),
                SmsTemplateService::tripConfirmation('T001', 'Central', '2025-09-27 15:30'),
                SmsTemplateService::driverAssigned('T001', 'Driver A', 'Car Model', 'ABC123', '+852 1234 5678'),
            ];
        }

        $estimate = SmsTemplateService::estimateCost($messages, $costPerSms);

        return response()->json([
            'cost_estimation' => $estimate,
            'recommendations' => [
                'optimize_for_single_sms' => $estimate['multi_part_messages'] > 0,
                'total_budget_needed' => $estimate['estimated_cost'],
                'savings_opportunity' => $estimate['multi_part_messages'] * $costPerSms
            ]
        ]);
    }

    /**
     * Example: Multilingual SMS support
     */
    public function sendMultilingualSms($userId, $language = 'en')
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Example: Send welcome message in user's preferred language
        $message = SmsTemplateService::welcome($user->name, $language);
        
        $result = $this->notificationService->sendSms($user->phone, $message, [
            'type' => 'multilingual_welcome',
            'language' => $language,
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Multilingual SMS sent',
            'language' => $language,
            'content' => $message,
            'result' => $result
        ]);
    }
}
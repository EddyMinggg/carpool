<?php

namespace App\Services;

class SmsTemplateService
{
    /**
     * SMS Templates for different scenarios
     */

    /**
     * OTP Verification Template
     */
    public static function otpVerification(string $otpCode, int $expiryMinutes = 5, string $language = 'en'): string
    {
        $templates = [
            'en' => "Your Carpool verification code is {$otpCode}. Valid for {$expiryMinutes} minutes. Never share this code. If you didn't request this, ignore this message.",
            'zh' => "您的共乘平台验证码是 {$otpCode}。有效期 {$expiryMinutes} 分钟。请勿分享此验证码。",
            'zh-HK' => "您的共乘平台驗證碼係 {$otpCode}。有效期 {$expiryMinutes} 分鐘。請勿分享呢個驗證碼。"
        ];

        return $templates[$language] ?? $templates['en'];
    }

    /**
     * Welcome Message Template
     */
    public static function welcome(string $username, string $language = 'en'): string
    {
        $templates = [
            'en' => "Welcome to Carpool, {$username}! Your account is now active. Start sharing rides and saving money. Download our app: snowpins.com",
            'zh' => "欢迎使用共乘平台，{$username}！您的账户已激活。开始共享出行，节省费用。下载我们的应用：snowpins.com",
            'zh-HK' => "歡迎使用共乘平台，{$username}！您嘅賬戶已經激活。開始共享出行，節省費用。下載我們嘅應用：snowpins.com"
        ];

        return $templates[$language] ?? $templates['en'];
    }

    /**
     * Trip Confirmation Template
     */
    public static function tripConfirmation(string $tripId, string $destination, string $departureTime): string
    {
        return "Trip confirmed! ID: {$tripId}. Going to {$destination} on {$departureTime}. Check app for pickup details. Safe travels!";
    }

    /**
     * Trip Reminder Template (1 hour before)
     */
    public static function tripReminder(string $tripId, string $destination, string $pickupTime, string $pickupLocation): string
    {
        return "🚗 Trip reminder: Pickup in 1 hour at {$pickupLocation} for {$destination}. Trip ID: {$tripId}. Be ready by {$pickupTime}!";
    }

    /**
     * Driver Assignment Template
     */
    public static function driverAssigned(string $tripId, string $driverName, string $carModel, string $plateNumber, string $driverPhone): string
    {
        return "Driver assigned! {$driverName} in {$carModel} ({$plateNumber}) will pick you up. Contact: {$driverPhone}. Trip ID: {$tripId}";
    }

    /**
     * Payment Confirmation Template
     */
    public static function paymentConfirmation(string $tripId, float $amount, string $currency = 'HKD'): string
    {
        return "Payment confirmed! {$currency} {$amount} for trip {$tripId}. Receipt available in app. Thank you for using Carpool!";
    }

    /**
     * Emergency Alert Template
     */
    public static function emergencyAlert(string $tripId): string
    {
        return "🚨 EMERGENCY ALERT: Trip {$tripId}. If this is a real emergency, call 999 immediately. Your location is being tracked.";
    }

    /**
     * Trip Cancellation Template
     */
    public static function tripCancellation(string $tripId, string $reason = null): string
    {
        if ($reason) {
            return "Trip {$tripId} has been cancelled. Reason: {$reason}. You will receive a full refund within 3-5 business days.";
        }
        return "Trip {$tripId} has been cancelled. You will receive a full refund within 3-5 business days.";
    }

    /**
     * Driver en route notification
     */
    public static function driverEnRoute(string $driverName, int $etaMinutes, string $tripId): string
    {
        return "🚗 {$driverName} is on the way! ETA: {$etaMinutes} minutes. Trip ID: {$tripId}. Please be ready at pickup location.";
    }

    /**
     * Driver arrived notification
     */
    public static function driverArrived(string $driverName, string $carModel, string $plateNumber): string
    {
        return "🚗 {$driverName} has arrived! Look for {$carModel} ({$plateNumber}). Please board the vehicle promptly.";
    }

    /**
     * Trip completed notification
     */
    public static function tripCompleted(string $tripId, string $rating = null): string
    {
        $baseMessage = "Trip {$tripId} completed successfully! Thank you for using Carpool.";
        if ($rating) {
            $baseMessage .= " You rated this trip {$rating} stars.";
        }
        $baseMessage .= " Safe travels!";
        return $baseMessage;
    }

    /**
     * Password Reset Template
     */
    public static function passwordReset(string $resetCode): string
    {
        return "Your Carpool password reset code is {$resetCode}. Valid for 15 minutes. Never share this code. If you didn't request this, ignore this message.";
    }

    /**
     * Ride Share Invitation Template
     */
    public static function rideShareInvitation(string $inviterName, string $destination, string $departureTime, string $appLink): string
    {
        return "{$inviterName} invited you to share a ride to {$destination} on {$departureTime}. Join the trip: {$appLink}";
    }

    /**
     * Team Join Notification - Golden Hour (Fixed Price)
     */
    public static function teamJoinGoldenHour(string $newMemberPhone, int $currentCount, int $maxPeople, string $destination, string $price): string
    {
        return "🌟 新成員加入！隊伍現有 {$currentCount}/{$maxPeople} 人前往 {$destination}。黃金時段固定價 HK\${$price}，1人即可出發！新成員：{$newMemberPhone}";
    }

    /**
     * Team Join Notification - Regular Hour (Dynamic Pricing)
     */
    public static function teamJoinRegularHour(string $newMemberPhone, int $currentCount, int $maxPeople, string $destination, string $basePrice, string $discountPrice = null): string
    {
        $message = "⏰ 新成員加入！隊伍現有 {$currentCount}/{$maxPeople} 人前往 {$destination}。";
        
        if ($currentCount >= 4 && $discountPrice) {
            $message .= "已達4人，享受優惠價 HK\${$discountPrice}/人！";
        } elseif ($currentCount == 3) {
            $message .= "還差1人就可享優惠價！目前 HK\${$basePrice}/人";
        } else {
            $message .= "基價 HK\${$basePrice}/人，4人可享優惠！";
        }
        
        $message .= "新成員：{$newMemberPhone}";
        return $message;
    }

    /**
     * Team Full Notification
     */
    public static function teamFull(string $destination, int $teamCount, string $finalPrice): string
    {
        return "🎉 隊伍已滿！{$teamCount}人隊伍前往 {$destination}，最終價格 HK\${$finalPrice}/人。請準備出發！";
    }

    /**
     * Team Near Full Notification (3/4 people)
     */
    public static function teamNearFull(string $destination, string $currentPrice, string $discountPrice): string
    {
        return "🔥 隊伍3/4人！還差1人前往 {$destination} 就可享優惠價 HK\${$discountPrice}/人（原價 HK\${$currentPrice}/人）。快邀請朋友！";
    }

    /**
     * Multilingual Support - Get template in different languages
     */
    public static function getTemplate(string $templateName, array $params, string $language = 'en'): string
    {
        $templates = [
            'en' => [
                'otp' => "Your Carpool verification code is {otp}. Valid for {minutes} minutes. Never share this code.",
                'welcome' => "Welcome to Carpool, {username}! Your account is now active.",
                'trip_reminder' => "🚗 Trip reminder: Pickup in 1 hour at {pickup} for {destination}. Trip ID: {tripId}",
            ],
            'zh' => [
                'otp' => "您的共乘平台验证码是 {otp}。有效期 {minutes} 分钟。请勿分享此验证码。",
                'welcome' => "欢迎使用共乘平台，{username}！您的账户已激活。",
                'trip_reminder' => "🚗 行程提醒：1小时后在 {pickup} 接送前往 {destination}。行程编号：{tripId}",
            ],
            'zh-HK' => [
                'otp' => "您的共乘平台驗證碼係 {otp}。有效期 {minutes} 分鐘。請勿分享呢個驗證碼。",
                'welcome' => "歡迎使用共乘平台，{username}！您嘅賬戶已經激活。",
                'trip_reminder' => "🚗 行程提醒：1小時後喺 {pickup} 接送前往 {destination}。行程編號：{tripId}",
            ]
        ];

        $template = $templates[$language][$templateName] ?? $templates['en'][$templateName] ?? '';
        
        foreach ($params as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }

    /**
     * SMS Length Validation (160 characters for single SMS)
     */
    public static function validateSmsLength(string $message): array
    {
        $length = mb_strlen($message);
        $parts = (int) ceil($length / 160); // Ensure integer type
        
        return [
            'message' => $message,
            'length' => $length,
            'parts' => $parts,
            'is_single' => $parts === 1,
            'cost_estimate' => $parts, // Usually 1 credit per part
            'warning' => $parts > 1 ? "Message will be sent as {$parts} parts" : null
        ];
    }

    /**
     * Smart Template Selector based on context
     */
    public static function getContextualTemplate(string $context, array $data = []): string
    {
        return match($context) {
            'registration' => self::otpVerification($data['otp'], $data['expiry'] ?? 5),
            'login_verification' => self::otpVerification($data['otp'], $data['expiry'] ?? 5),
            'trip_booking' => self::tripConfirmation($data['trip_id'], $data['destination'], $data['departure_time']),
            'trip_reminder_1h' => self::tripReminder($data['trip_id'], $data['destination'], $data['pickup_time'], $data['pickup_location']),
            'driver_assigned' => self::driverAssigned($data['trip_id'], $data['driver_name'], $data['car_model'], $data['plate_number'], $data['phone']),
            'driver_enroute' => self::driverEnRoute($data['driver_name'], $data['eta_minutes'], $data['trip_id']),
            'driver_arrived' => self::driverArrived($data['driver_name'], $data['car_model'], $data['plate_number']),
            'trip_completed' => self::tripCompleted($data['trip_id'], $data['rating'] ?? null),
            'payment_success' => self::paymentConfirmation($data['trip_id'], $data['amount'], $data['currency'] ?? 'HKD'),
            'trip_cancelled' => self::tripCancellation($data['trip_id'], $data['reason'] ?? null),
            'emergency' => self::emergencyAlert($data['trip_id']),
            'welcome' => self::welcome($data['username']),
            'password_reset' => self::passwordReset($data['reset_code']),
            default => "Carpool notification: " . ($data['message'] ?? 'Update available in app')
        };
    }

    /**
     * Batch Template Generator for multiple recipients
     */
    public static function generateBatchTemplates(string $templateType, array $recipients, array $commonData = []): array
    {
        $templates = [];
        
        foreach ($recipients as $recipient) {
            $data = array_merge($commonData, $recipient);
            $templates[] = [
                'phone' => $recipient['phone'],
                'message' => self::getContextualTemplate($templateType, $data),
                'user_id' => $recipient['user_id'] ?? null
            ];
        }
        
        return $templates;
    }

    /**
     * Cost Estimation for SMS campaigns
     */
    public static function estimateCost(array $messages, float $costPerSms = 0.05): array
    {
        $totalParts = 0;
        $singleSms = 0;
        $multiSms = 0;
        
        foreach ($messages as $message) {
            $validation = self::validateSmsLength($message);
            $totalParts += $validation['parts'];
            
            if ($validation['is_single']) {
                $singleSms++;
            } else {
                $multiSms++;
            }
        }
        
        return [
            'total_messages' => count($messages),
            'total_sms_parts' => $totalParts,
            'single_part_messages' => $singleSms,
            'multi_part_messages' => $multiSms,
            'estimated_cost' => $totalParts * $costPerSms,
            'cost_per_sms' => $costPerSms
        ];
    }
}
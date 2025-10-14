<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\TripJoin;
use App\Models\Payment;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TripNotificationService
{
    private $twilioService;

    public function __construct()
    {
        $this->twilioService = new TwilioService();
    }

    /**
     * Send WhatsApp notification to existing confirmed members when a new member joins
     */
    public function notifyNewMemberJoined(Payment $payment): void
    {
        try {
            $trip = $payment->trip;
            
            // Get all confirmed members (excluding the new member who just joined)
            $confirmedMembers = $this->getConfirmedMembers($trip, $payment);
            
            if ($confirmedMembers->isEmpty()) {
                Log::info('No existing confirmed members to notify', [
                    'trip_id' => $trip->id,
                    'new_payment_id' => $payment->id
                ]);
                return;
            }

            // Get current trip status
            $tripStatus = $this->calculateTripStatus($trip);
            
            // Generate appropriate message based on trip type
            $message = $this->generateNotificationMessage($trip, $tripStatus, $payment);
            
            // Send notification to each confirmed member
            foreach ($confirmedMembers as $member) {
                $this->sendNotificationToMember($member, $message, $trip);
            }

            Log::info('New member join notifications sent', [
                'trip_id' => $trip->id,
                'new_payment_id' => $payment->id,
                'notified_members' => $confirmedMembers->count(),
                'trip_status' => $tripStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send new member notifications', [
                'trip_id' => $payment->trip_id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get all confirmed members except the new joiner
     */
    private function getConfirmedMembers(Trip $trip, Payment $payment): \Illuminate\Database\Eloquent\Collection
    {
        return TripJoin::where('trip_id', $trip->id)
            ->where('payment_confirmation', true)
            ->where('user_phone', '!=', $payment->user_phone) // Exclude new member
            ->get();
    }

    /**
     * Calculate current trip status
     */
    private function calculateTripStatus(Trip $trip): array
    {
        $confirmedCount = TripJoin::where('trip_id', $trip->id)
            ->where('payment_confirmation', true)
            ->count();

        return [
            'confirmed_count' => $confirmedCount,
            'max_passengers' => $trip->max_people, // Use correct field name
            'is_full' => $confirmedCount >= $trip->max_people,
            'needs_more' => max(0, $trip->max_people - $confirmedCount),
            'trip_type' => $trip->type,
            'price_per_person' => $trip->price_per_person,
            'four_person_discount' => $trip->four_person_discount ?? 0
        ];
    }

    /**
     * Generate notification message based on trip type and current status
     */
    private function generateNotificationMessage(Trip $trip, array $status, Payment $payment): string
    {
        $newMemberPhone = $this->formatPhoneForDisplay($payment->user_phone);
        $tripRoute = $this->formatTripRoute($trip);
        $departureTime = Carbon::parse($trip->planned_departure_time)->format('m月d日 H:i');

        if ($status['trip_type'] === 'golden') {
            return $this->generateGoldenTimeMessage($tripRoute, $departureTime, $newMemberPhone, $status);
        } else {
            // For 'normal' and 'fixed' types
            return $this->generateRegularTimeMessage($tripRoute, $departureTime, $newMemberPhone, $status);
        }
    }

    /**
     * Generate message for golden time trips
     */
    private function generateGoldenTimeMessage(string $route, string $time, string $newMember, array $status): string
    {
        return "🎉 *新成員加入通知*\n\n" .
               "📍 行程：{$route}\n" .
               "🕐 出發時間：{$time}\n" .
               "👤 新成員：{$newMember} 已成功加入\n\n" .
               "👥 *目前狀況：*\n" .
               "• 已確認人數：{$status['confirmed_count']}/{$status['max_passengers']} 人\n" .
               "• 黃金時段固定價格：HK\$250/人\n\n" .
               ($status['is_full'] 
                   ? "✅ *行程已滿員！*\n準備出發，請留意司機聯絡。" 
                   : "⏳ 還需要 {$status['needs_more']} 人即可滿員\n\n繼續招募中...") .
               "\n\n💬 如有疑問請聯繫客服";
    }

    /**
     * Generate message for regular time trips (with discount structure)
     */
    private function generateRegularTimeMessage(string $route, string $time, string $newMember, array $status): string
    {
        $baseMessage = "🎉 *新成員加入通知*\n\n" .
                      "📍 行程：{$route}\n" .
                      "🕐 出發時間：{$time}\n" .
                      "👤 新成員：{$newMember} 已成功加入\n\n" .
                      "👥 *目前狀況：*\n" .
                      "• 已確認人數：{$status['confirmed_count']}/{$status['max_passengers']} 人\n";

        if ($status['confirmed_count'] == 2) {
            return $baseMessage .
                   "• 基礎價格：HK\${$status['price_per_person']}/人\n\n" .
                   "🎯 *需要再多 1 人即可成功拼車！*\n" .
                   "當有第 3 人加入時，行程即可確認出發。\n\n" .
                   "💡 溫馨提示：4 人滿員時每人可享 HK\${$status['four_person_discount']} 優惠\n\n" .
                   "💬 如有疑問請聯繫客服";
        } elseif ($status['confirmed_count'] == 3) {
            return $baseMessage .
                   "• 目前價格：HK\${$status['price_per_person']}/人\n\n" .
                   "✅ *拼車成功！* 行程確認出發\n\n" .
                   "🎁 *優惠提醒：*\n" .
                   "再多 1 人加入即可享受 4 人優惠，\n" .
                   "每人減 HK\${$status['four_person_discount']} 🎉\n\n" .
                   "💬 如有疑問請聯繫客服";
        } elseif ($status['confirmed_count'] >= 4) {
            $discountedPrice = $status['price_per_person'] - $status['four_person_discount'];
            return $baseMessage .
                   "• 優惠價格：HK\${$discountedPrice}/人 (省 HK\${$status['four_person_discount']})\n\n" .
                   "🎊 *滿員優惠！* 行程確認出發\n" .
                   "恭喜獲得 4 人滿員優惠價格！\n\n" .
                   ($status['is_full'] 
                       ? "✅ 行程已滿，準備出發\n請留意司機聯絡。" 
                       : "還有空位，歡迎更多朋友加入！") .
                   "\n\n💬 如有疑問請聯繫客服";
        } else {
            // This shouldn't happen in normal flow, but just in case
            return $baseMessage .
                   "• 基礎價格：HK\${$status['price_per_person']}/人\n\n" .
                   "🎯 繼續招募成員中...\n\n" .
                   "💬 如有疑問請聯繫客服";
        }
    }

    /**
     * Send WhatsApp notification to a specific member
     */
    private function sendNotificationToMember(TripJoin $member, string $message, Trip $trip): void
    {
        try {
            $result = $this->twilioService->sendWhatsApp($member->user_phone, $message);
            
            if ($result['success']) {
                Log::info('Member notification sent successfully', [
                    'trip_id' => $trip->id,
                    'member_phone' => $member->user_phone,
                    'message_id' => $result['message_id'] ?? null
                ]);
            } else {
                Log::warning('Member notification failed', [
                    'trip_id' => $trip->id,
                    'member_phone' => $member->user_phone,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending member notification', [
                'trip_id' => $trip->id,
                'member_phone' => $member->user_phone,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Format phone number for display
     */
    private function formatPhoneForDisplay(string $phone): string
    {
        // Hide middle digits for privacy: +85257220308 -> +852****0308
        if (strlen($phone) >= 8) {
            return substr($phone, 0, 4) . '****' . substr($phone, -4);
        }
        return $phone;
    }

    /**
     * Format trip route for display
     */
    private function formatTripRoute(Trip $trip): string
    {
        return $trip->pickup_location . ' → ' . $trip->dropoff_location;
    }
}
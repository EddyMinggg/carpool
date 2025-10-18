<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\User;
use App\Models\TripJoin;

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
            'en' => "Your " . config('app.name') . " verification code is: {$otpCode}. The code will expire in {$expiryMinutes} minutes. For your security, please do not share this code with anyone.",
            'ch' => "【" . config('app.name') . "】您的验证码为： {$otpCode}。有效期 {$expiryMinutes} 分钟。请勿分享此验证码。",
            'hk' => "【" . config('app.name') . "】您的驗證碼為： {$otpCode}。有效期 {$expiryMinutes} 分鐘。請勿分享此驗證碼。"
        ];

        return $templates[$language] ?? $templates['en'];
    }

    public static function goldenTimeJoinMessage(Trip $trip): string
    {
        $allTripJoins = TripJoin::where('trip_id', $trip->id)->whereNot('has_left', 1);
        $allTripJoinsCount = $allTripJoins->count();
        $latestTimestamp = $allTripJoins->max('created_at');
        $latestRecords = $allTripJoins->where('created_at', $latestTimestamp)->get();
        $latestRecordsCount = $latestRecords->count();

        $latestUser = null;
        if ($latestRecordsCount < 2) {
            $latestUser = User::where('phone', $latestRecords->user_phone)->first();
        }

        return "🎉 *新成員加入通知*\n\n" .
            "📍 行程：{$trip->dropoff_location}\n" .
            "🕐 出發時間：{$trip->planned_departure_time}\n" .
            ($latestUser ? "👤 新成員：{$latestUser->username} 已成功加入\n\n" : "👤 {$latestRecordsCount}名新成員已成功加入\n\n") .
            "👥 *目前狀況：*\n" .
            "• 已確認人數：{$allTripJoinsCount}/{$trip->max_passengers} 人\n" .
            "• 黃金時段固定價格：HK\$250/人\n\n"  .
            "💬 如有疑問請聯繫客服";
    }

    /**
     * Generate message for regular time trips (with discount structure)
     */
    public static function regularTimeJoinMessage(Trip $trip): string
    {
        $allTripJoins = TripJoin::where('trip_id', $trip->id)->whereNot('has_left', 1);
        $allTripJoinsCount = $allTripJoins->count();

        $latestTimestamp = $allTripJoins->max('created_at');
        $latestRecords = $allTripJoins->where('created_at', $latestTimestamp)->get();
        $latestRecordsCount = $latestRecords->count();


        $latestUser = null;
        if ($latestRecordsCount < 2) {
            $latestUser = User::where('phone', $latestRecords->first()->user_phone)->first();
        }

        $baseMessage = "🎉 *新成員加入通知*\n\n" .
            "📍 行程：{$trip->dropoff_location}\n" .
            "🕐 出發時間：{$trip->planned_departure_time}\n" .
            ($latestUser ? "👤 新成員：{$latestUser->username} 已成功加入\n\n" : "👤 {$latestRecordsCount}名新成員已成功加入\n\n") .
            "👥 *目前狀況：*\n" .
            "• 已確認人數：{$allTripJoinsCount}/{$trip->max_passengers} 人\n";

        if ($allTripJoinsCount == 2) {
            return $baseMessage .
                "• 基礎價格：HK\${$trip->price_per_person}/人\n\n" .
                "🎯 *需要再多 1 人即可成功拼車！*\n" .
                "當有第 3 人加入時，行程即可確認出發。\n\n" .
                "💡 溫馨提示：4 人滿員時每人可享 HK\${$trip->four_person_discount} 優惠\n\n" .
                "💬 如有疑問請聯繫客服";
        } elseif ($allTripJoinsCount == 3) {
            return $baseMessage .
                "• 目前價格：HK\${$trip->price_per_person}/人\n\n" .
                "✅ *拼車成功！* 行程確認出發\n\n" .
                "🎁 *優惠提醒：*\n" .
                "再多 1 人加入即可享受 4 人優惠，\n" .
                "每人減 HK\${$trip->price_per_person} 🎉\n\n" .
                "💬 如有疑問請聯繫客服";
        } elseif ($allTripJoinsCount == 4) {
            $discountedPrice = $trip->price_per_person - $trip->four_person_discount;
            return $baseMessage .
                "• 優惠價格：HK\${$discountedPrice}/人 (省 HK\${$trip->price_per_person})\n\n" .
                "🎊 *滿員優惠！* 行程確認出發\n" .
                "恭喜獲得 4 人滿員優惠價格！\n\n" .
                ($allTripJoinsCount == $trip->max_people
                    ? "✅ 行程已滿，準備出發\n請留意司機聯絡。"
                    : "還有空位，歡迎更多朋友加入！") .
                "\n\n💬 如有疑問請聯繫客服";
        } else {
            // This shouldn't happen in normal flow, but just in case
            return $baseMessage .
                "• 基礎價格：HK\${$trip->price_per_person}/人\n\n" .
                "🎯 繼續招募成員中...\n\n" .
                "💬 如有疑問請聯繫客服";
        }
    }
    public static function goldenTimeLeaveMessage(Trip $trip, string $leftUserPhone, ?string $leftUserName = null): string
    {
        // Use provided user info instead of querying
        $leftUserDisplayName = $leftUserName ?? $leftUserPhone;

        $allTripJoinsCount = TripJoin::where('trip_id', $trip->id)->whereNot('has_left', 1)->count();

        return "*成員退出通知*\n\n" .
            "📍 行程：{$trip->dropoff_location}\n" .
            "🕐 出發時間：{$trip->planned_departure_time}\n" .
            "👤 成員：{$leftUserDisplayName} 已退出\n\n" .
            "👥 *目前狀況：*\n" .
            "• 已確認人數：{$allTripJoinsCount}/{$trip->max_passengers} 人\n" .
            "• 黃金時段固定價格：HK\$250/人\n\n"  .
            "💬 如有疑問請聯繫客服";
    }

    /**
     * Generate message for regular time trips (with discount structure)
     */
    public static function regularTimeLeaveMessage(Trip $trip, string $leftUserPhone, ?string $leftUserName = null): string
    {
        // Use provided user info instead of querying
        $leftUserDisplayName = $leftUserName ?? $leftUserPhone;

        $allTripJoinsCount = TripJoin::where('trip_id', $trip->id)->whereNot('has_left', 1)->count();

        $baseMessage = "*成員退出通知*\n\n" .
            "📍 行程：{$trip->dropoff_location}\n" .
            "🕐 出發時間：{$trip->planned_departure_time}\n" .
            "👤 成員：{$leftUserDisplayName} 已退出\n\n" .
            "👥 *目前狀況：*\n" .
            "• 已確認人數：{$allTripJoinsCount}/{$trip->max_passengers} 人\n";

        if ($allTripJoinsCount == 2) {
            return $baseMessage .
                "• 基礎價格：HK\${$trip->price_per_person}/人\n\n" .
                "🎯 *需要再多 1 人即可成功拼車！*\n" .
                "當有第 3 人加入時，行程即可確認出發。\n\n" .
                "💡 溫馨提示：4 人滿員時每人可享 HK\${$trip->four_person_discount} 優惠\n\n" .
                "💬 如有疑問請聯繫客服";
        } elseif ($allTripJoinsCount == 3) {
            return $baseMessage .
                "• 目前價格：HK\${$trip->price_per_person}/人\n\n" .
                "✅ *拼車成功！* 行程確認出發\n\n" .
                "🎁 *優惠提醒：*\n" .
                "再多 1 人加入即可享受 4 人優惠，\n" .
                "每人減 HK\${$trip->price_per_person} 🎉\n\n" .
                "💬 如有疑問請聯繫客服";
        } elseif ($allTripJoinsCount == 4) {
            $discountedPrice = $trip->price_per_person - $trip->four_person_discount;
            return $baseMessage .
                "• 優惠價格：HK\${$discountedPrice}/人 (省 HK\${$trip->price_per_person})\n\n" .
                "🎊 *滿員優惠！* 行程確認出發\n" .
                "恭喜獲得 4 人滿員優惠價格！\n\n" .
                ($allTripJoinsCount == $trip->max_people
                    ? "✅ 行程已滿，準備出發\n請留意司機聯絡。"
                    : "還有空位，歡迎更多朋友加入！") .
                "\n\n💬 如有疑問請聯繫客服";
        } else {
            // This shouldn't happen in normal flow, but just in case
            return $baseMessage .
                "• 基礎價格：HK\${$trip->price_per_person}/人\n\n" .
                "🎯 繼續招募成員中...\n\n" .
                "💬 如有疑問請聯繫客服";
        }
    }
}

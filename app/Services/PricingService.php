<?php

namespace App\Services;

use App\Models\Trip;

class PricingService
{
    /**
     * 計算行程總價格
     */
    public function calculateTripTotalPrice(Trip $trip, int $passengerCount): float
    {
        if ($trip->is_golden_hour) {
            return $this->calculateGoldenHourPrice($trip, $passengerCount);
        } else {
            return $this->calculateRegularHourPrice($trip, $passengerCount);
        }
    }

    /**
     * 計算每人實際支付金額
     */
    public function calculatePricePerPerson(Trip $trip, int $passengerCount): float
    {
        $totalPrice = $this->calculateTripTotalPrice($trip, $passengerCount);
        return $totalPrice / $passengerCount;
    }

    /**
     * 黃金時段定價
     */
    private function calculateGoldenHourPrice(Trip $trip, int $passengerCount): float
    {
        // 黃金時段：固定價格 HK$250/人，無優惠
        return $trip->price_per_person * $passengerCount;
    }

    /**
     * 非黃金時段定價
     */
    private function calculateRegularHourPrice(Trip $trip, int $passengerCount): float
    {
        // 非黃金時段：4人或以上享有優惠
        if ($passengerCount >= 4) {
            $discountedPrice = $trip->price_per_person - $trip->four_person_discount;
            return $discountedPrice * $passengerCount;
        }
        
        return $trip->price_per_person * $passengerCount;
    }

    /**
     * 檢查是否可以出發
     */
    public function canTripDepart(Trip $trip, int $currentPassengers): array
    {
        $minPassengers = $trip->is_golden_hour ? 1 : $trip->min_passengers;
        
        if ($trip->is_golden_hour) {
            return [
                'can_depart' => $currentPassengers >= 1,
                'message' => $currentPassengers >= 1 ? '已達成團條件' : '等待乘客加入',
                'needs_admin_action' => false
            ];
        } else {
            // 非黃金時段邏輯
            if ($currentPassengers >= 3) {
                return [
                    'can_depart' => true,
                    'message' => '已達成團條件',
                    'needs_admin_action' => false
                ];
            } elseif ($currentPassengers == 2) {
                return [
                    'can_depart' => false,
                    'message' => '需要管理員確認是否補貼開車',
                    'needs_admin_action' => true,
                    'supplement_required' => 100 // 每人需補貼的金額
                ];
            } else {
                return [
                    'can_depart' => false,
                    'message' => '人數不足，將安排退款',
                    'needs_admin_action' => true,
                    'needs_refund' => true
                ];
            }
        }
    }

    /**
     * 計算退款金額
     */
    public function calculateRefundAmount(Trip $trip, int $passengerCount): float
    {
        if ($trip->is_golden_hour) {
            // 黃金時段不需要退款
            return 0;
        }
        
        // 非黃金時段 1人或2人不補貼的情況需要全額退款
        return $trip->price_per_person;
    }

    /**
     * 獲取定價摘要信息
     */
    public function getPricingSummary(Trip $trip): array
    {
        return [
            'is_golden_hour' => $trip->is_golden_hour,
            'hour_type' => $trip->is_golden_hour ? '黃金時段' : '非黃金時段',
            'base_price' => $trip->price_per_person,
            'min_passengers' => $trip->is_golden_hour ? 1 : $trip->min_passengers,
            'max_passengers' => $trip->max_people,
            'has_discount' => !$trip->is_golden_hour && $trip->four_person_discount > 0,
            'discount_amount' => $trip->four_person_discount,
            'discount_condition' => '4人或以上',
            'pricing_examples' => $this->generatePricingExamples($trip)
        ];
    }

    /**
     * 生成定價示例
     */
    private function generatePricingExamples(Trip $trip): array
    {
        $examples = [];
        
        for ($i = 1; $i <= $trip->max_people; $i++) {
            $totalPrice = $this->calculateTripTotalPrice($trip, $i);
            $pricePerPerson = $totalPrice / $i;
            
            $status = 'can_depart';
            $note = '';
            
            if (!$trip->is_golden_hour) {
                if ($i == 1) {
                    $status = 'refund';
                    $note = '退款';
                } elseif ($i == 2) {
                    $status = 'admin_decision';
                    $note = '需管理員確認補貼';
                }
            }
            
            $examples[$i] = [
                'passenger_count' => $i,
                'total_price' => $totalPrice,
                'price_per_person' => $pricePerPerson,
                'status' => $status,
                'note' => $note
            ];
        }
        
        return $examples;
    }
}
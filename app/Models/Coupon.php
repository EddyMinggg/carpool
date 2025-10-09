<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'valid_from',
        'valid_to',
        'enabled',
        'usage_limit',
        'used_count',
        'per_user_limit',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'enabled' => 'boolean',
        'discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'per_user_limit' => 'integer',
    ];

    /**
     * 關聯到使用記錄
     */
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * 檢查用戶是否可以使用此優惠券
     */
    public function canBeUsedByUser(string $userPhone): bool
    {
        // 如果沒有設定每用戶限制，則可以使用
        if (!$this->per_user_limit) {
            return true;
        }

        // 檢查該用戶已使用此優惠券的次數
        $userUsageCount = $this->usages()
            ->where('user_phone', $userPhone)
            ->count();

        return $userUsageCount < $this->per_user_limit;
    }

    /**
     * 獲取用戶使用此優惠券的次數
     */
    public function getUserUsageCount(string $userPhone): int
    {
        return $this->usages()
            ->where('user_phone', $userPhone)
            ->count();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripJoin extends Model
{
    use HasFactory;

    // Primary key configuration - 使用自增ID作為主鍵
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'trip_id',
        'user_phone',
        'join_time',
        'user_fee',
        'pickup_location',
        'reference_code',
        'payment_confirmed',
        'payment_confirmed_at',
        'confirmed_by',
        'has_left'
    ];

    protected $casts = [
        'join_time' => 'datetime',
        'user_fee' => 'decimal:2',
        'payment_confirmed' => 'boolean',
        'payment_confirmed_at' => 'datetime',
    ];

    // Relationship: TripJoin belongs to a Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    // Relationship: TripJoin belongs to a User (through phone number)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_phone', 'phone');
    }



    // Relationship: Admin who confirmed the payment
    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by', 'id');
    }

    // Check if payment is confirmed
    public function isPaymentConfirmed(): bool
    {
        return $this->payment_confirmed;
    }

    // Scope for confirmed payments
    public function scopeConfirmed($query)
    {
        return $query->where('payment_confirmed', true);
    }

    // Scope for pending payments
    public function scopePending($query)
    {
        return $query->where('payment_confirmed', false);
    }

    // 檢查付款是否已超時（30分鐘）
    public function isPaymentTimeout(): bool
    {
        if ($this->payment_confirmed) {
            return false; // 已付款，不算超時
        }

        // 檢查創建時間是否超過30分鐘
        return $this->created_at->lt(now()->subMinutes(30));
    }

    // Scope: 有效的待付款記錄（30分鐘內）
    public function scopeValidPending($query)
    {
        return $query->where('payment_confirmed', false)
            ->where('created_at', '>=', now()->subMinutes(30));
    }

    // Scope: 超時的待付款記錄（超過30分鐘未付款）
    public function scopeExpiredPending($query)
    {
        return $query->where('payment_confirmed', false)
            ->where('created_at', '<', now()->subMinutes(30));
    }

    // 檢查用戶是否可以再次預訂（如果之前的預訂已超時）
    public static function canUserRebook(string $userPhone, int $tripId): bool
    {
        $existingJoin = self::where('user_phone', $userPhone)
            ->where('trip_id', $tripId)
            ->first();

        if (!$existingJoin) {
            return true; // 沒有記錄，可以預訂
        }

        if ($existingJoin->payment_confirmed) {
            return false; // 已付款確認，不能重複預訂
        }

        // 如果未付款且已超時，可以重新預訂
        return $existingJoin->isPaymentTimeout();
    }
}

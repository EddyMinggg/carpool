<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'user_phone',
        'payment_id',
        'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    /**
     * 關聯到優惠券
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * 關聯到付款記錄
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * 獲取用戶（通過電話號碼）
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_phone', 'phone');
    }
}
{
    //
}

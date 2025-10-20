<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'trip_id',
        'user_phone',
        'amount',
        'type',
        'paid',
        'group_size',
        'coupon_code',
        'coupon_discount'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_phone', 'phone');
    }

    // Coupon usage relationship
    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class, 'payment_id', 'id');
    }

    // Get all trip joins for this payment's group booking
    public function tripJoins()
    {
        if ($this->type === 'group' && $this->group_size > 1) {
            // For group bookings, return multiple trip joins for the same trip
            return $this->hasMany(TripJoin::class, 'trip_id', 'trip_id')
                       ->where('payment_confirmation', true)
                       ->limit($this->group_size);
        } else {
            // For individual bookings, return the specific user's trip join
            return $this->hasOne(TripJoin::class, 'trip_id', 'trip_id')
                       ->where('user_phone', $this->user_phone);
        }
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('paid', false);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('paid', true);
    }

    public function scopeIndividual($query)
    {
        return $query->where('type', 'individual');
    }

    public function scopeGroup($query)
    {
        return $query->where('type', 'group');
    }
}

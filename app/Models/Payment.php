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
        'pickup_location',
        'paid',
        'group_size'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Get all trip joins for this payment's group booking
    public function tripJoins()
    {
        if ($this->type === 'group_full_payment' && $this->group_size > 1) {
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

    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    public function scopeRemaining($query)
    {
        return $query->where('type', 'remaining');
    }
}

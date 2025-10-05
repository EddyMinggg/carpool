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
        'group_size',
        'parent_payment_id'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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

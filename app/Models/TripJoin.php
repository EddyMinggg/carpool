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
        'vote_info'
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

    // Check if user has voted
    public function hasVoted(): bool
    {
        return !empty($this->vote_info) && isset($this->vote_info['vote_result']);
    }

    // Get vote result (agree/disagree)
    public function getVoteResult(): ?string
    {
        return $this->vote_info['vote_result'] ?? null;
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
}

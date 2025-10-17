<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PhoneVerification extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'phone',
        'otp_code',
        'expires_at',
        'is_verified',
        'ip_address',
        'attempts',
        'user_data',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
        'user_data' => 'array',
    ];

    /**
     * Check if the OTP has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if max attempts reached
     */
    public function hasMaxAttemptsReached(): bool
    {
        return $this->attempts >= 3;
    }

    /**
     * Increment verification attempts
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Mark as verified
     */
    public function markAsVerified(): bool
    {
        return $this->update([
            'is_verified' => true,
        ]);
    }

    /**
     * Scope for active (not expired, not verified) verifications
     */
    public function scopeActive($query)
    {
        return $query->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope for specific phone number
     */
    public function scopeForPhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }
}

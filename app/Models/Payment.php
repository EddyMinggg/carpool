<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'trip_id',
        'user_id',
        'payment_amount',
        'payment_status',
        'payment_time',
        'payment_note'
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'payment_time' => 'datetime',
    ];

    // Status constants
    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_PAID = 'paid';
    public const STATUS_REFUNDED = 'refunded';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_UNPAID => 'Unpaid',
            self::STATUS_PAID => 'Paid',
            self::STATUS_REFUNDED => 'Refunded'
        ];
    }

    // Relationship: Payment belongs to a Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }

    // Relationship: Payment belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Mark payment as paid
    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => self::STATUS_PAID,
            'payment_time' => now()
        ]);
    }
}

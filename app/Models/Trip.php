<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'creator_id',
        'pickup_location',
        'dropoff_location',
        'planned_departure_time',
        'max_people',
        'min_passengers',
        'price_per_person',
        'four_person_discount',
        'trip_status',
        'type',
        'invitation_code'
    ];

    protected $casts = [
        'planned_departure_time' => 'datetime',
        'actual_departure_time' => 'datetime',
        'price_per_person' => 'decimal:2',
        'four_person_discount' => 'decimal:2',
    ];

    // Status constants
    public const STATUS_AWAITING = 'awaiting';
    public const STATUS_DEPARTED = 'departed';
    public const STATUS_CHARGING = 'charging';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    // Type constants
    public const TYPE_GOLDEN = 'golden';
    public const TYPE_NORMAL = 'normal';
    public const TYPE_FIXED = 'fixed';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_AWAITING => 'Awaiting',
            self::STATUS_DEPARTED => 'Departed',
            self::STATUS_CHARGING => 'Charging',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
    }

    // Relationship: Trip belongs to a creator (User)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    // Relationship: Trip has many joins
    public function joins()
    {
        return $this->hasMany(TripJoin::class, 'trip_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'trip_id', 'id');
    }

    // Driver relationships
    public function tripDriver()
    {
        return $this->hasOne(TripDriver::class, 'trip_id', 'id');
    }

    public function tripDrivers()
    {
        return $this->hasMany(TripDriver::class, 'trip_id', 'id');
    }

    public function assignedDriver()
    {
        return $this->belongsToMany(User::class, 'trip_drivers', 'trip_id', 'driver_id')
            ->withPivot(['status', 'notes', 'assigned_at', 'confirmed_at'])
            ->withTimestamps();
    }

    // 新的定價邏輯方法
    public function isGoldenHour(): bool
    {
        return $this->type === self::TYPE_GOLDEN;
    }
    
    public function getMinPassengers(): int
    {
        return $this->isGoldenHour() ? 1 : $this->min_passengers;
    }
    
    public function calculateTotalPrice(int $passengerCount): float
    {
        if ($this->isGoldenHour()) {
            // 黃金時段：固定價格，無優惠
            return $this->price_per_person * $passengerCount;
        } else {
            // 非黃金時段：4人有優惠
            if ($passengerCount >= 4 && $this->four_person_discount > 0) {
                $totalBeforeDiscount = $this->price_per_person * $passengerCount;
                return $totalBeforeDiscount - $this->four_person_discount;
            }
            return $this->price_per_person * $passengerCount;
        }
    }
    
    public function getEffectivePricePerPerson(int $passengerCount): float
    {
        if (!$this->isGoldenHour() && $passengerCount >= 4 && $this->four_person_discount > 0) {
            $totalWithDiscount = $this->calculateTotalPrice($passengerCount);
            return (float) ($totalWithDiscount / $passengerCount);
        }
        return (float) $this->price_per_person;
    }
    
    public function canDepart(int $currentPassengers): bool
    {
        return $currentPassengers >= $this->getMinPassengers();
    }
    
    public function getHourTypeName(): string
    {
        return $this->isGoldenHour() ? '黃金時段' : '普通時段';
    }

    public function getTypeDisplayName(): string
    {
        return match($this->type) {
            self::TYPE_GOLDEN => '🌟 Golden Hour',
            self::TYPE_NORMAL => '⏰ Regular Hour', 
            self::TYPE_FIXED => '📋 Fixed',
            default => $this->type
        };
    }

    // Helper methods for driver
    public function hasDriver(): bool
    {
        return $this->tripDriver()->exists();
    }

    public function getDriver()
    {
        return $this->assignedDriver()->first();
    }

    public function getDriverAssignment()
    {
        return $this->tripDriver()->first();
    }
}

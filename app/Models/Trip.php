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
        'actual_departure_time',
        'max_people',
        'base_price',
        'trip_status',
        'type',
        'invitation_code'
    ];

    protected $casts = [
        'planned_departure_time' => 'datetime',
        'actual_departure_time' => 'datetime',
        'base_price' => 'decimal:2'
    ];

    // Status constants
    public const STATUS_AWAITING = 'awaiting';
    public const STATUS_DEPARTED = 'departed';
    public const STATUS_CHARGING = 'charging';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

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

    public function assignedDriver()
    {
        return $this->belongsToMany(User::class, 'trip_drivers', 'trip_id', 'driver_id')
            ->withPivot(['status', 'notes', 'assigned_at', 'confirmed_at'])
            ->withTimestamps();
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

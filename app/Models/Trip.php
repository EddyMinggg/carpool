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
        'creator_id', 'start_place', 'end_place', 'plan_departure_time',
        'actual_departure_time', 'max_people', 'is_private', 'trip_status', 'base_price'
    ];

    protected $casts = [
        'plan_departure_time' => 'datetime',
        'actual_departure_time' => 'datetime',
        'is_private' => 'boolean',
        'base_price' => 'decimal:2'
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_VOTING = 'voting';
    public const STATUS_DEPARTED = 'departed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_VOTING => 'Voting',
            self::STATUS_DEPARTED => 'Departed',
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
}

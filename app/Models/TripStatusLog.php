<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripStatusLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'trip_id',
        'old_status',
        'new_status',
        'operate_user_id',
        'operate_time',
        'remark'
    ];

    protected $casts = [
        'operate_time' => 'datetime',
    ];

    // Relationship: Log belongs to a Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }

    // Relationship: Log belongs to an operator (User)
    public function operator()
    {
        return $this->belongsTo(User::class, 'operate_user_id', 'user_id');
    }

    // Create a new status log entry
    public static function createLog($tripId, $oldStatus, $newStatus, $userId, $remark): self
    {
        return self::create([
            'trip_id' => $tripId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'operate_user_id' => $userId,
            'operate_time' => now(),
            'remark' => $remark
        ]);
    }
}

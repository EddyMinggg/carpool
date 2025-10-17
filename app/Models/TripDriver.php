<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripDriver extends Model
{
    protected $fillable = [
        'trip_id',
        'driver_id', 
        'status',
        'notes',
        'assigned_at',
        'confirmed_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    // Relationships
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Scopes
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeConfirmed($query) 
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    // Helper methods - 簡化後的狀態判斷
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function canCancel(): bool
    {
        // 可以取消，除非 trip 已經完成
        return $this->trip->trip_status !== 'completed';
    }

    // 獲取 trip 真實狀態的便利方法
    public function getTripStatus(): string
    {
        return $this->trip->trip_status;
    }
}

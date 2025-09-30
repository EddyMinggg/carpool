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

    // Helper methods
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function canCancel(): bool
    {
        return in_array($this->status, ['assigned', 'confirmed']);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripJoin extends Model
{
    use HasFactory;

    // Primary key configuration (composite key handled in migration)
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'trip_id',
        'user_id',
        'join_role',
        'join_time',
        'user_fee',
        'pickup_location', // 新增
        'vote_info'
    ];

    protected $casts = [
        'join_time' => 'datetime',
        'user_fee' => 'decimal:2',
        'vote_info' => 'array' // Automatically cast JSON to array
    ];

    // Relationship: TripJoin belongs to a Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }

    // Relationship: TripJoin belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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
}

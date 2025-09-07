<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'valid_from',
        'valid_to',
        'enabled',
        'usage_limit',
        'used_count',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'enabled' => 'boolean',
        'discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];
}

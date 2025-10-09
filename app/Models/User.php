<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'phone_verified_at',
        'user_role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Role constants
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_DRIVER = 'driver';

    // Check if user is admin (includes super admin)
    public function isAdmin(): bool
    {
        return $this->user_role === self::ROLE_ADMIN || $this->user_role === self::ROLE_SUPER_ADMIN;
    }

    // Check if user is super admin
    public function isSuperAdmin(): bool
    {
        return $this->user_role === self::ROLE_SUPER_ADMIN;
    }

    // Check if user is regular admin (not super admin)
    public function isRegularAdmin(): bool
    {
        return $this->user_role === self::ROLE_ADMIN;
    }

    // Get role name
    public function getRoleName(): string
    {
        return match ($this->user_role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            default => 'User'
        };
    }

    // Check if user is driver
    public function isDriver(): bool
    {
        return $this->user_role === self::ROLE_DRIVER;
    }

    /**
     * Route notifications for the Vonage channel.
     */
    public function routeNotificationForVonage(Notification $notification): string
    {
        return $this->phone;
    }

    // Check if phone is verified
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    // Mark phone as verified
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    // Driver relationships
    public function driverTrips()
    {
        return $this->hasMany(TripDriver::class, 'driver_id');
    }

    public function assignedTrips()
    {
        return $this->belongsToMany(Trip::class, 'trip_drivers', 'driver_id', 'trip_id')
            ->withPivot(['status', 'notes', 'assigned_at', 'confirmed_at'])
            ->withTimestamps();
    }

    /**
     * Send the email verification notification with fast delivery.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\FastVerifyEmail);
    }
}

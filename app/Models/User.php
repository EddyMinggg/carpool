<?php

namespace App\Models;

use App\Channels\SmsChannel;
use App\Channels\WhatsAppChannel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
// 移除 SoftDeletes 如果不需要軟刪除功能
// use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // 移除 SoftDeletes

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
        'user_role',
        'active',
        'notification_channel'
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
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
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
     * Route notifications for the Twilio channel.
     */
    public function routeNotificationForWhatsApp(): string
    {
        return $this->phone;
    }

    public function routeNotificationForSms(): string
    {
        return $this->phone;
    }

    /**
     * Get the user's preferred notification channel.
     */
    protected function notificationChannel(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => match ($value) {
                'sms' => SmsChannel::class,
                'whatsapp' => WhatsAppChannel::Class,
                default => SmsChannel::Class,
            },
        );
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

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->active == 1;
    }

    /**
     * Activate the user.
     */
    public function activate()
    {
        $this->update(['active' => 1]);
    }

    /**
     * Deactivate the user.
     */
    public function deactivate()
    {
        $this->update(['active' => 0]);
    }
}

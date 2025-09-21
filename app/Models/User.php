<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'is_admin'
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
        'password' => 'hashed',
    ];

    // Role constants
    public const ROLE_USER = 0;
    public const ROLE_ADMIN = 1;
    public const ROLE_SUPER_ADMIN = 2;

    // Check if user is admin (includes super admin)
    public function isAdmin(): bool
    {
        return $this->is_admin >= self::ROLE_ADMIN;
    }

    // Check if user is super admin
    public function isSuperAdmin(): bool
    {
        return $this->is_admin === self::ROLE_SUPER_ADMIN;
    }

    // Check if user is regular admin (not super admin)
    public function isRegularAdmin(): bool
    {
        return $this->is_admin === self::ROLE_ADMIN;
    }

    // Get role name
    public function getRoleName(): string
    {
        return match($this->is_admin) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            default => 'User'
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, Notifiable;

    // FIXED: Proper mass assignment protection
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // FIXED: Hide sensitive fields
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // FIXED: Password hashing enabled
    ];

    // REMOVED: Vulnerable methods removed
    // - findByEmailUnsafe
    // - updateProfile
    // - isAdmin (use strict comparison in services)

    // Relationships
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
}

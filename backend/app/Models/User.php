<?php

namespace App\Models;

use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Mass assignment protection
    protected $fillable = [
        'name',
        'email',
        'password',
        'NIK',
        'role',
        'email_verified_at',
        'remember_token',
    ];

    // Hide sensitive fields
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    // Relationships
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
    public function getPatientIdAttribute()
    {
        return optional($this->patient)->id;
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    // Hash password otomatis saat di-set
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

}

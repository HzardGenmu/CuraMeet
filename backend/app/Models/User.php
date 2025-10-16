<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- 1. Tambahkan ini

class User extends Authenticatable
{
    // 2. Gunakan trait HasApiTokens
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // 3. Amankan $fillable
    protected $fillable = [
        'name',
        'email',
        'password',
        'NIK',
        'role',
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
    // 4. Gunakan 'hashed' untuk password
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // <-- Ini akan otomatis hash password saat di-set
    ];

    // 5. Mutator setPasswordAttribute() sekarang bisa dihapus karena sudah ditangani oleh $casts

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

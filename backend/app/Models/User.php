<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, Notifiable;

    // VULNERABILITY 1: Mass Assignment - No $guarded, allowing all fields
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'id', // Dangerous!
        'email_verified_at', // Dangerous!
        'remember_token', // Dangerous!
    ];

    protected $hidden = [
        // VULNERABILITY 2: Password visible in responses
        // 'password', // Commented out - password will be visible!
        // 'remember_token', // Commented out
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // VULNERABILITY 3: No password hashing
        // 'password' => 'hashed', // Commented out - passwords stored in plain text!
    ];

    // VULNERABILITY 4: SQL Injection in custom method
    public static function findByEmailUnsafe($email)
    {
        return DB::select("SELECT * FROM users WHERE email = '$email'")[0] ?? null;
    }

    // VULNERABILITY 5: No input validation
    public function updateProfile($data)
    {
        // Direct database update without validation
        DB::update("UPDATE users SET name = '{$data['name']}', email = '{$data['email']}' WHERE id = {$this->id}");
    }

    // Relationships
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // VULNERABILITY 6: Weak role checking
    public function isAdmin()
    {
        // Using == instead of === allows type juggling attacks
        return $this->role == 'admin';
    }
}

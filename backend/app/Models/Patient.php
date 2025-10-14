<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // FIXED: Proper mass assignment protection
    protected $fillable = [
        'user_id',
        'full_name',
        'NIK',
        'picture',
        'allergies',
        'disease_histories',
        'email',
        'phone',
        'address',
    ];

    // REMOVED: Vulnerable methods removed
    // - setPictureAttribute (no directory traversal)
    // - searchByName (use service layer with proper queries)
    // - getAllergiesRaw (use Eloquent attributes)

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}

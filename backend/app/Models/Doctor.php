<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    // FIXED: Proper mass assignment protection
    protected $fillable = [
        'user_id',
        'str_number',
        'full_name',
        'specialist',
        'polyclinic',
        'available_time',
    ];

    // REMOVED: Vulnerable methods removed
    // - getBySpecialist (use service layer)
    // - getSchedule (use service layer with proper queries)
    // - exportData (use secure export functionality)

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

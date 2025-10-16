<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'time_appointment',
        'status',
        'doctor_note',      // raw, XSS vulnerability tetap
        'patient_note',     // raw, XSS vulnerability tetap
        'cancellation_reason',
        'cancelled_by',
    ];

    protected $casts = [
        'time_appointment' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

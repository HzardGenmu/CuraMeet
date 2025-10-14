<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // FIXED: Proper mass assignment protection
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'time_appointment',
        'date_appointment',
        'status',
        'room',
        'cancel_reason',
        'cancelled_by',
    ];

    protected $casts = [
        'time_appointment' => 'datetime',
        'date_appointment' => 'date',
    ];

    // REMOVED: Vulnerable methods removed
    // - getByDate (use service layer)
    // - cancelAppointment (use service layer with proper authorization)
    // - getPatientInfo (use relationships)

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

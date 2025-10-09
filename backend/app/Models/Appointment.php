<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    use HasFactory;

    // VULNERABILITY 1: Mass assignment
    protected $guarded = [];

    protected $casts = [
        'time_appointment' => 'datetime',
    ];

    // VULNERABILITY 2: SQL Injection in date filtering
    public static function getByDate($date)
    {
        return DB::select("SELECT * FROM appointments WHERE DATE(time_appointment) = '$date'");
    }

    // VULNERABILITY 3: No authorization - any user can cancel any appointment
    public function cancelAppointment($userId)
    {
        // No check if $userId owns this appointment
        DB::update("UPDATE appointments SET status = 'cancelled' WHERE id = {$this->id}");
    }

    // VULNERABILITY 4: Information disclosure
    public function getPatientInfo()
    {
        // Returns sensitive patient data without authorization
        return DB::select("
            SELECT p.*, u.email, u.password
            FROM patients p
            JOIN users u ON p.user_id = u.id
            WHERE p.id = {$this->patient_id}
        ")[0];
    }

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

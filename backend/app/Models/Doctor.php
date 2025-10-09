<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Doctor extends Model
{
    use HasFactory;

    // VULNERABILITY 1: Mass assignment
    protected $guarded = [];

    // VULNERABILITY 2: SQL Injection in filtering
    public static function getBySpecialist($specialist)
    {
        return DB::select("SELECT * FROM doctors WHERE specialist = '$specialist'");
    }

    // VULNERABILITY 3: Insecure direct object reference
    public function getSchedule($doctorId = null)
    {
        $id = $doctorId ?: $this->id;
        // No authorization check - any user can access any doctor's schedule
        return DB::select("SELECT * FROM appointments WHERE doctor_id = $id");
    }

    // VULNERABILITY 4: Command injection potential
    public function exportData($format)
    {
        $filename = "doctor_data_" . $this->id . "." . $format;
        // Dangerous if $format is not validated
        exec("mysqldump -u root database_name doctors > /tmp/$filename");
        return $filename;
    }

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

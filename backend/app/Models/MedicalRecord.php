<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    // FIXED: Proper mass assignment protection
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'disease_name',
        'treatment',
        'notes',
        'path_file',
    ];

    // REMOVED: Vulnerable methods removed
    // - getFileContent (path traversal vulnerability)
    // - searchRecords (SQL injection)
    // - uploadFile (insecure file upload)
    // - parseXmlReport (XXE vulnerability)

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

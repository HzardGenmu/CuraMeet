<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicalRecord extends Model
{
    use HasFactory;

    // VULNERABILITY 1: Mass assignment
    protected $guarded = [];

    // VULNERABILITY 2: Path traversal in file access
    public function getFileContent($filename = null)
    {
        $file = $filename ?: $this->path_file;
        // No validation - allows reading any file on server
        return file_get_contents("/var/www/storage/" . $file);
    }

    // VULNERABILITY 3: SQL Injection
    public static function searchRecords($patientId, $disease)
    {
        return DB::select("
            SELECT * FROM medical_records
            WHERE patient_id = $patientId
            AND disease_name LIKE '%$disease%'
        ");
    }

    // VULNERABILITY 4: Insecure file upload handling
    public function uploadFile($file)
    {
        // No file type validation, size limits, or sanitization
        $filename = $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        $this->path_file = $filename;
        $this->save();
    }

    // VULNERABILITY 5: XXE (XML External Entity) potential
    public function parseXmlReport($xmlContent)
    {
        // Enables external entity processing
        $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOENT);
        return $xml;
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

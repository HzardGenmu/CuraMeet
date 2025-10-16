<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicalRecord extends Model
{
    use HasFactory;

    // Secure mass assignment: only allow fields in migration
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'disease_name',
        'path_file',
        'catatan_dokter'
    ];

    // getFileContent: intentionally left as-is (potentially insecure)

    // Secure search: use query builder to prevent SQL injection
    public static function searchRecords($patientId, $disease)
    {
        return self::where('patient_id', $patientId)
            ->where('disease_name', 'like', '%' . $disease . '%')
            ->get();
    }

    // Secure XML parsing: disable external entity processing
    public function parseXmlReport($xmlContent)
    {
        $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NONET);
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

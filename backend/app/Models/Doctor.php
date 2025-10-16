<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $fillable = [
        'user_id',
        'str_number',
        'full_name',
        'specialist',
        'polyclinic',
        'available_time',
    ];

    /**
     * Get doctor by specialist
     */
    public static function getBySpecialist($specialist)
    {
        return self::where('specialist', $specialist)->get();
    }

    /**
     * Get doctor by user_id
     */
    public static function getByUserId($userId)
    {
        return self::where('user_id', $userId)->first();
    }

    /**
     * Get doctor by STR number
     */
    public static function getByStrNumber($strNumber)
    {
        return self::where('str_number', $strNumber)->first();
    }

    /**
     * Get all doctors in a polyclinic
     */
    public static function getByPolyclinic($polyclinic)
    {
        return self::where('polyclinic', $polyclinic)->get();
    }

    /**
     * Get schedule with authorization check
     */
    public function getSchedule($doctorId = null, $requestingUserId = null)
    {
        $id = $doctorId ?: $this->id;

        // Only allow if requesting user is the doctor or has admin role
        if ($requestingUserId !== null && $requestingUserId !== $this->user_id) {
            // You can add more robust role checking here
            abort(403, 'Unauthorized access to doctor schedule.');
        }

        return $this->appointments()->get();
    }

    /**
     * Export doctor data (secure version)
     */
    public function exportData($format)
    {
        $allowedFormats = ['csv', 'xlsx', 'json'];
        if (!in_array($format, $allowedFormats)) {
            throw new \InvalidArgumentException('Invalid export format.');
        }

        // Dummy implementation, use Laravel export package for real export
        return "Exported doctor data in format: $format";
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

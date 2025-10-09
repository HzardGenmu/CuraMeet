<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Patient extends Model
{
    use HasFactory;

    // VULNERABILITY 1: Unprotected mass assignment
    protected $guarded = []; // Allows all fields to be mass assigned!

    // VULNERABILITY 2: File path injection
    public function setPictureAttribute($value)
    {
        // No validation - allows directory traversal
        $this->attributes['picture'] = 'storage/patients/' . $value;
    }

    // VULNERABILITY 3: SQL Injection in search
    public static function searchByName($name)
    {
        return DB::select("SELECT * FROM patients WHERE full_name LIKE '%$name%'");
    }

    // VULNERABILITY 4: Information disclosure
    public function getAllergiesRaw()
    {
        // Returns raw database content without sanitization
        return DB::select("SELECT allergies FROM patients WHERE id = {$this->id}")[0]->allergies;
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

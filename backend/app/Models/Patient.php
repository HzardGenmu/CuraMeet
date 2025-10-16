<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder; // Import Builder
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'NIK',
        'picture',
        'allergies',
        'disease_histories'
    ];

    // Tidak perlu $hidden jika password ada di model User, bukan di sini.
    // Tapi jika ada data sensitif lain di model Patient, tambahkan di sini.
    protected $hidden = [
        // 'NIK', // Contoh jika NIK dianggap sensitif dan tidak selalu ingin ditampilkan
    ];

    /**
     * Mutator untuk path gambar. Sudah benar.
     */
    public function setPictureAttribute($value)
    {
        $filename = basename($value);
        $this->attributes['picture'] = 'storage/patients/' . $filename;
    }

    /**
     * FIX: Ubah searchByName menjadi Local Scope.
     * Awali nama fungsi dengan "scope".
     */
    public function scopeSearchByName(Builder $query, string $name): Builder
    {
        return $query->where('full_name', 'like', '%' . $name . '%');
    }

    // --- Relasi (Sudah benar) ---
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

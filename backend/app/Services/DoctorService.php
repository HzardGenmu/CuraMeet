<?php
namespace App\Services;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;

class DoctorService
{

    /**
     * Get doctor by userId
     */
    public function getDoctorByUserId($userId)
    {
        $doctor = Doctor::where('user_id', $userId)->with('user')->first();

        if (!$doctor) {
            return [
                'success' => false,
                'message' => 'Doctor not found'
            ];
        }

        return [
            'success' => true,
            'doctor' => $doctor
        ];
    }

    /**
     * Get doctor by ID (secure)
     */
    public function getDoctorById($doctorId)
    {
        $doctor = Doctor::with('user')->find($doctorId);

        if (!$doctor) {
            return [
                'success' => false,
                'message' => 'Doctor not found'
            ];
        }

        return [
            'success' => true,
            'doctor' => $doctor
        ];
    }

    /**
     * Get doctors by name (secure)
     */
    public function getDoctorsByName($name)
    {
        $doctors = Doctor::where('full_name', 'like', '%' . $name . '%')->get();

        return [
            'success' => true,
            'doctors' => $doctors,
            'count' => $doctors->count()
        ];
    }

    /**
     * Mengembalikan daftar dokter (id, nama, spesialisasi, available_time, dst)
     */
    public function listDoctors()
    {
        $doctors = Doctor::all();

        return [
            'success' => true,
            'doctors' => $doctors,
            'count' => $doctors->count()
        ];
    }
}

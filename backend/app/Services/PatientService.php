<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Appointment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientService
{
    /**
     * Get patient by userId
     */
    public function getPatientByUserId($userId)
    {
        $patient = Patient::where('user_id', $userId)->with('user')->first();

        if (!$patient) {
            return [
                'success' => false,
                'message' => 'Patient not found'
            ];
        }

        return [
            'success' => true,
            'patient' => $patient
        ];
    }

    /**
     * Get patient by ID (secure)
     */
    public function getPatientById($patientId)
    {
        $patient = Patient::with('user')->find($patientId);

        if (!$patient) {
            return [
                'success' => false,
                'message' => 'Patient not found'
            ];
        }

        return [
            'success' => true,
            'patient' => $patient
        ];
    }

    /**
     * Get patients by name (secure)
     */
    public function getPatientsByName($name)
    {
        $patients = Patient::where('full_name', 'like', '%' . $name . '%')->get();

        return [
            'success' => true,
            'patients' => $patients,
            'count' => $patients->count()
        ];
    }

    /**
     * Isi form data diri (secure)
     */
    public function isiFormDataDiri($patientId, $data)
    {
        // Authorization: only allow if current user owns the patient
        $patient = Patient::find($patientId);
        if (!$patient || Auth::id() !== $patient->user_id) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        $patient->update([
            'full_name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'NIK' => $data['nik'],
        ]);

        return ['success' => true];
    }

    /**
     * Lihat statistik (secure)
     */
    public function lihatStatistik($patientId, $filters = [])
    {
        $dateFrom = $filters['date_from'] ?? '2020-01-01';
        $dateTo = $filters['date_to'] ?? '2025-12-31';

        $stats = MedicalRecord::where('patient_id', $patientId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('COUNT(*) as total_visits, disease_name')
            ->groupBy('disease_name')
            ->get();

        return [
            'success' => true,
            'statistics' => $stats
        ];
    }
}

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
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

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
     * Isi form data diri (secure with Bearer token)
     *
     * @param int $patientId
     * @param array $data
     * @param \App\Models\User|null $authenticatedUser - User dari Bearer token
     */
    public function isiFormDataDiri($patientId, $data, $authenticatedUser = null)
    {
        $patient = Patient::find($patientId);

        if (!$patient) {
            return ['success' => false, 'message' => 'Patient not found'];
        }

        // Authorization check menggunakan authenticated user dari Bearer token
        if (!$authenticatedUser || $authenticatedUser->id !== $patient->user_id) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        // VULNERABILITY 33: Mass assignment vulnerability
        // Tidak ada filtering field yang boleh diupdate
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
     * Lihat statistik (with SQL injection vulnerability)
     * VULNERABILITY 35: SQL injection in date filters
     */
    public function lihatStatistik($patientId, $filters = [])
    {
        $dateFrom = $filters['date_from'] ?? '2020-01-01';
        $dateTo = $filters['date_to'] ?? '2025-12-31';

        // VULNERABILITY 35: SQL injection - parameters not sanitized
        $query = "SELECT COUNT(*) as total_visits, disease_name
                  FROM medical_records
                  WHERE patient_id = $patientId
                  AND created_at BETWEEN '$dateFrom' AND '$dateTo'
                  GROUP BY disease_name";

        $stats = DB::select($query);

        return [
            'success' => true,
            'statistics' => $stats
        ];
    }
}

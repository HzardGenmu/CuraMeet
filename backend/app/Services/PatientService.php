<?php

namespace App\Services;

use App\Models\Patient;

use Illuminate\Support\Facades\DB;
use App\Models\User;

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
        $patients = Patient::with('user')
            ->where('full_name', 'like', '%' . $name . '%')
            ->get();

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

        if (!$authenticatedUser || $authenticatedUser->id !== $patient->user_id) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        $patientFields = [
            'full_name',
            'NIK',
            'picture',
            'allergies',
            'disease_histories'
        ];

        $userFields = [
            'name',
            'email'
        ];

        $patientUpdate = [];
        foreach ($patientFields as $field) {
            if ($field === 'full_name' && isset($data['name'])) {
                $patientUpdate['full_name'] = $data['name'];
            } elseif ($field === 'NIK' && isset($data['nik'])) {
                $patientUpdate['NIK'] = $data['nik'];
            } elseif (isset($data[$field])) {
                $patientUpdate[$field] = $data[$field];
            }
        }
        if (!empty($patientUpdate)) {
            $patient->update($patientUpdate);
        }

        $user = User::find($patient->user_id);
        if ($user) {
            $userUpdate = [];
            foreach ($userFields as $field) {
                if ($field === 'name' && isset($data['name'])) {
                    $userUpdate['name'] = $data['name'];
                } elseif (isset($data[$field])) {
                    $userUpdate[$field] = $data[$field];
                }
            }
            if (!empty($userUpdate)) {
                $user->update($userUpdate);
            }
        }

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
        $query = "SELECT COUNT(*) as disease_name
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

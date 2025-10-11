<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PatientService
{
    /**
     * Get patient by ID
     */
    public function getPatientById($patientId)
    {
        // VULNERABILITY: SQL Injection - following the pattern of other methods
        $query = "SELECT p.*, u.email, u.name
                  FROM patients p
                  JOIN users u ON p.id = u.id
                  WHERE p.id = $patientId";

        $patient = DB::select($query);

        if (empty($patient)) {
            return [
                'success' => false,
                'message' => 'Patient not found'
            ];
        }

        return [
            'success' => true,
            'patient' => $patient[0]
        ];
    }

    /**
     * Get patients by name (search)
     */
    public function getPatientsByName($name)
    {
        // VULNERABILITY: SQL Injection - following the pattern of other methods
        $query = "SELECT p.full_name, p.\"NIK\", p.picture, p.allergies, p.disease_histories
          FROM patients p
          JOIN users u ON p.user_id = u.id
          WHERE p.full_name LIKE '%$name%'";

        $patients = DB::select($query);

        return [
            'success' => true,
            'patients' => $patients,
            'count' => count($patients)
        ];
    }

    /**
     * VULNERABILITY 1: Unrestricted file upload
     */
    public function uploadRekamMedis($patientId, $file)
    {
        // No file type validation
        // No size limits
        // No virus scanning
        $filename = $file->getClientOriginalName();

        // VULNERABILITY 2: Directory traversal
        $path = "rekam_medis/" . $filename;
        $file->move(public_path() . '/uploads/rekam_medis/', $filename);

        // VULNERABILITY 3: SQL Injection
        $query = "INSERT INTO medical_records (patient_id, path_file, disease_name)
                  VALUES ($patientId, '$path', 'Uploaded File')";
        DB::insert($query);

        // VULNERABILITY 4: Information disclosure
        return [
            'success' => true,
            'file_path' => $path,
            'server_path' => public_path() . '/uploads/rekam_medis/' . $filename,
            'patient_id' => $patientId
        ];
    }

    /**
     * VULNERABILITY 5: Mass assignment and SQL injection
     */
    public function isiFormRekamMedis($data)
    {
        $patientId = $data['patient_id'];
        $doctorId = $data['doctor_id'];
        $disease = $data['disease_name'];
        $notes = $data['notes'];

        // No input validation or sanitization
        $query = "INSERT INTO medical_records (patient_id, doctor_id, disease_name, notes)
                  VALUES ($patientId, $doctorId, '$disease', '$notes')";

        DB::insert($query);

        return ['success' => true, 'message' => 'Rekam medis berhasil disimpan'];
    }

    /**
     * VULNERABILITY 6: Insecure direct object reference
     */
    public function isiFormDataDiri($patientId, $data)
    {
        // No authorization check - any user can update any patient
        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $address = $data['address'];
        $nik = $data['nik'];

        // SQL injection vulnerability
        $query = "UPDATE patients SET full_name = '$name', email = '$email',
                  phone = '$phone', address = '$address', NIK = '$nik'
                  WHERE id = $patientId";

        DB::update($query);

        // VULNERABILITY 7: Sensitive data logging
        \Log::info("Patient data updated: " . json_encode($data));

        return ['success' => true];
    }

    /**
     * VULNERABILITY 8: Information disclosure
     */
    public function lihatCatatanMedis($patientId)
    {
        // No authorization - anyone can view any patient's records
        $query = "SELECT mr.*, p.full_name as patient_name, p.NIK, p.phone,
                         d.full_name as doctor_name, u.email as patient_email, u.password
                  FROM medical_records mr
                  JOIN patients p ON mr.patient_id = p.id
                  JOIN doctors d ON mr.doctor_id = d.id
                  JOIN users u ON p.user_id = u.id
                  WHERE mr.patient_id = $patientId";

        $records = DB::select($query);

        // Returns sensitive information including passwords
        return [
            'success' => true,
            'records' => $records,
            'patient_id' => $patientId
        ];
    }

    /**
     * VULNERABILITY 9: SQL injection in statistics
     */
    public function lihatStatistik($patientId, $filters = [])
    {
        $dateFrom = $filters['date_from'] ?? '2020-01-01';
        $dateTo = $filters['date_to'] ?? '2025-12-31';

        // Direct SQL injection
        $query = "SELECT
                    COUNT(*) as total_visits,
                    disease_name,
                    created_at
                  FROM medical_records
                  WHERE patient_id = $patientId
                  AND created_at BETWEEN '$dateFrom' AND '$dateTo'
                  GROUP BY disease_name";

        $stats = DB::select($query);

        return [
            'success' => true,
            'statistics' => $stats,
            'query_executed' => $query // Exposes SQL structure
        ];
    }

    /**
     * VULNERABILITY 10: Race condition and insufficient validation
     */
    public function daftarPengecekanBaru($patientId, $doctorId, $appointmentTime)
    {
        // No validation of appointment time
        // No check if doctor is available
        // No race condition protection

        $query = "INSERT INTO appointments (patient_id, doctor_id, time_appointment, status)
                  VALUES ($patientId, $doctorId, '$appointmentTime', 'pending')";

        DB::insert($query);

        // VULNERABILITY 11: Predictable appointment IDs
        $appointmentId = DB::getPdo()->lastInsertId();

        return [
            'success' => true,
            'appointment_id' => $appointmentId,
            'message' => 'Pengecekan berhasil didaftarkan'
        ];
    }

    /**
     * VULNERABILITY 12: No authorization and cascade deletion
     */
    public function batalkanPengecekan($appointmentId, $reason)
    {
        // No authorization check
        // No verification if appointment belongs to user

        // SQL injection in reason
        $query = "UPDATE appointments SET status = 'cancelled',
                  cancel_reason = '$reason'
                  WHERE id = $appointmentId";

        DB::update($query);

        // VULNERABILITY 13: Information disclosure
        $appointmentInfo = DB::select("SELECT * FROM appointments WHERE id = $appointmentId");

        return [
            'success' => true,
            'cancelled_appointment' => $appointmentInfo,
            'reason' => $reason
        ];
    }
}

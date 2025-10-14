<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PatientService
{
    /**
     * VULNERABILITY: IDOR - Get patient by ID without authorization
     * INTENTIONALLY KEPT - No authorization check
     */
    public function getPatientById($patientId)
    {
        // IDOR vulnerability kept, but fixed SQL injection
        $patient = DB::table('patients as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->where('p.id', $patientId)
            ->select('p.*', 'u.email', 'u.name')
            ->first();

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
     * Get patients by name (search)
     * FIXED: SQL injection using parameter binding
     */
    public function getPatientsByName($name)
    {
        $patients = DB::table('patients as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->where('p.full_name', 'LIKE', '%' . $name . '%')
            ->select('p.full_name', 'p.NIK', 'p.picture', 'p.allergies', 'p.disease_histories')
            ->get();

        return [
            'success' => true,
            'patients' => $patients,
            'count' => count($patients)
        ];
    }

    /**
     * FIXED: Secure file upload with validation
     */
    public function uploadRekamMedis($patientId, $file)
    {
        // FIXED: Validate file type
        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only PDF and images allowed.'];
        }

        // FIXED: Validate file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            return ['success' => false, 'message' => 'File too large. Maximum 5MB.'];
        }

        // FIXED: Generate secure filename
        $extension = $file->getClientOriginalExtension();
        $filename = 'record_' . $patientId . '_' . time() . '_' . Str::random(10) . '.' . $extension;
        $path = "rekam_medis/" . $filename;

        // Store file securely
        $file->move(public_path('uploads/rekam_medis/'), $filename);

        // FIXED: Use parameterized query
        DB::table('medical_records')->insert([
            'patient_id' => $patientId,
            'path_file' => $path,
            'disease_name' => 'Uploaded File',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // FIXED: Don't expose server paths
        return [
            'success' => true,
            'message' => 'File uploaded successfully',
            'file_name' => $filename
        ];
    }

    /**
     * VULNERABILITY: XSS in medical record notes - INTENTIONALLY KEPT
     * Notes field is not sanitized to allow XSS testing
     */
    public function isiFormRekamMedis($data)
    {
        $patientId = $data['patient_id'];
        $doctorId = $data['doctor_id'];
        $disease = $data['disease_name'];
        $notes = $data['notes']; // XSS vulnerability kept - no sanitization

        // FIXED: Use parameterized query to prevent SQL injection
        DB::table('medical_records')->insert([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'disease_name' => $disease,
            'notes' => $notes, // XSS vulnerability - unsanitized notes
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return ['success' => true, 'message' => 'Rekam medis berhasil disimpan'];
    }

    /**
     * VULNERABILITY: IDOR - Update patient data without authorization
     * INTENTIONALLY KEPT - No authorization check
     */
    public function isiFormDataDiri($patientId, $data)
    {
        // IDOR vulnerability kept - no authorization check
        // But FIXED: Use parameterized queries and don't log sensitive data
        
        DB::table('patients')
            ->where('id', $patientId)
            ->update([
                'full_name' => $data['name'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'NIK' => $data['nik'] ?? null,
                'updated_at' => now()
            ]);

        // FIXED: Don't log sensitive data
        \Log::info("Patient data updated", ['patient_id' => $patientId]);

        return ['success' => true, 'message' => 'Data updated successfully'];
    }

    /**
     * VULNERABILITY: IDOR - View medical records without authorization
     * INTENTIONALLY KEPT - No authorization check
     */
    public function lihatCatatanMedis($patientId)
    {
        // IDOR vulnerability kept - no authorization check
        // But FIXED: Don't expose passwords and use proper joins
        
        $records = DB::table('medical_records as mr')
            ->join('patients as p', 'mr.patient_id', '=', 'p.id')
            ->join('doctors as d', 'mr.doctor_id', '=', 'd.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->where('mr.patient_id', $patientId)
            ->select(
                'mr.*',
                'p.full_name as patient_name',
                'p.NIK',
                'p.phone',
                'd.full_name as doctor_name',
                'u.email as patient_email'
                // FIXED: Don't include password
            )
            ->get();

        return [
            'success' => true,
            'records' => $records,
            'patient_id' => $patientId
        ];
    }

    /**
     * FIXED: Statistics with proper parameterized queries
     */
    public function lihatStatistik($patientId, $filters = [])
    {
        $dateFrom = $filters['date_from'] ?? '2020-01-01';
        $dateTo = $filters['date_to'] ?? '2025-12-31';

        // FIXED: Use parameterized query
        $stats = DB::table('medical_records')
            ->where('patient_id', $patientId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('disease_name', DB::raw('COUNT(*) as total_visits'), 'created_at')
            ->groupBy('disease_name', 'created_at')
            ->get();

        // FIXED: Don't expose query structure
        return [
            'success' => true,
            'statistics' => $stats
        ];
    }

    /**
     * FIXED: Appointment creation with validation
     */
    public function daftarPengecekanBaru($patientId, $doctorId, $appointmentTime)
    {
        // FIXED: Validate appointment time
        if (strtotime($appointmentTime) < time()) {
            return [
                'success' => false,
                'message' => 'Appointment time must be in the future'
            ];
        }

        // FIXED: Use parameterized query
        $appointmentId = DB::table('appointments')->insertGetId([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'time_appointment' => $appointmentTime,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return [
            'success' => true,
            'appointment_id' => $appointmentId,
            'message' => 'Pengecekan berhasil didaftarkan'
        ];
    }

    /**
     * FIXED: Appointment cancellation with proper query
     */
    public function batalkanPengecekan($appointmentId, $reason)
    {
        // FIXED: Use parameterized query
        DB::table('appointments')
            ->where('id', $appointmentId)
            ->update([
                'status' => 'cancelled',
                'cancel_reason' => $reason,
                'updated_at' => now()
            ]);

        // FIXED: Don't expose sensitive information
        return [
            'success' => true,
            'message' => 'Appointment cancelled successfully'
        ];
    }
}

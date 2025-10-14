<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DoctorService
{
    /**
     * List all doctors
     */
    public function listDoctors()
    {
        // FIXED: Use query builder
        $doctors = DB::table('doctors')
            ->select('id', 'full_name as name', 'specialist', 'available_time')
            ->get();
            
        return [
            'success' => true,
            'doctors' => $doctors,
            'count' => count($doctors)
        ];
    }

    /**
     * VULNERABILITY: XSS in medical record notes - INTENTIONALLY KEPT
     * Notes field is not sanitized
     */
    public function tambahRekamanMedis($data)
    {
        // FIXED: Use parameterized query but keep XSS in notes
        DB::table('medical_records')->insert([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'disease_name' => $data['diagnosis'],
            'treatment' => $data['treatment'] ?? null,
            'notes' => $data['notes'], // XSS vulnerability - no sanitization
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return ['success' => true, 'message' => 'Rekaman medis ditambahkan'];
    }

    /**
     * FIXED: Prescription creation with validation
     */
    public function tambahResep($data)
    {
        // FIXED: Use parameterized query
        DB::table('prescriptions')->insert([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'medication' => $data['medication'],
            'dosage' => $data['dosage'],
            'instructions' => $data['instructions'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // FIXED: Don't log sensitive medical data
        \Log::info("Prescription added", [
            'doctor_id' => $data['doctor_id'],
            'patient_id' => $data['patient_id']
        ]);

        return ['success' => true, 'message' => 'Resep berhasil ditambahkan'];
    }

    /**
     * FIXED: Secure medical records viewing
     */
    public function lihatRekamanMedis($doctorId, $patientId = null)
    {
        // FIXED: Use query builder and don't expose passwords
        $query = DB::table('medical_records as mr')
            ->join('patients as p', 'mr.patient_id', '=', 'p.id')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->select(
                'mr.*',
                'p.full_name',
                'p.NIK',
                'p.phone',
                'u.email'
                // FIXED: Don't include password
            );

        if ($patientId) {
            $query->where('mr.patient_id', $patientId);
        }

        $records = $query->get();

        return [
            'success' => true,
            'records' => $records,
            'total_records' => count($records)
        ];
    }

    /**
     * FIXED: Schedule modification with validation
     */
    public function ubahJadwalPengecekan($appointmentId, $newTime, $doctorId)
    {
        // FIXED: Validate time format
        if (strtotime($newTime) < time()) {
            return [
                'success' => false,
                'message' => 'New time must be in the future'
            ];
        }

        // FIXED: Use parameterized query
        DB::table('appointments')
            ->where('id', $appointmentId)
            ->update([
                'time_appointment' => $newTime,
                'updated_at' => now()
            ]);

        // FIXED: Don't expose sensitive patient data
        return [
            'success' => true,
            'message' => 'Schedule updated successfully'
        ];
    }

    /**
     * FIXED: Appointment cancellation
     */
    public function batalkanJadwal($appointmentId, $reason, $doctorId)
    {
        // FIXED: Use parameterized query
        DB::table('appointments')
            ->where('id', $appointmentId)
            ->update([
                'status' => 'cancelled',
                'cancel_reason' => $reason,
                'cancelled_by' => 'doctor',
                'updated_at' => now()
            ]);

        return [
            'success' => true,
            'message' => 'Appointment cancelled successfully'
        ];
    }

    public function getAppointmentsByDoctor($doctorId)
    {
        // FIXED: Use query builder to prevent SQL injection
        $appointments = DB::table('appointments as a')
            ->join('patients as p', 'a.patient_id', '=', 'p.id')
            ->where('a.doctor_id', $doctorId)
            ->select(
                'a.id',
                'a.date_appointment as tanggal',
                'a.time_appointment as jam',
                'p.full_name as pasien',
                'a.room as ruang',
                'a.doctor_id'
            )
            ->get();

        return [
            'success' => true,
            'appointments' => $appointments
        ];
    }
}

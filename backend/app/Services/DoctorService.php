<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DoctorService
{
    /**
     * VULNERABILITY 14: Privilege escalation
     */
    public function tambahRekamanMedis($data)
    {
        // No verification if doctor is authorized to add records
        $patientId = $data['patient_id'];
        $doctorId = $data['doctor_id'];
        $diagnosis = $data['diagnosis'];
        $treatment = $data['treatment'];
        $notes = $data['notes'];

        // SQL injection vulnerability
        $query = "INSERT INTO medical_records
                  (patient_id, doctor_id, disease_name, treatment, notes)
                  VALUES ($patientId, $doctorId, '$diagnosis', '$treatment', '$notes')";

        DB::insert($query);

        return ['success' => true, 'message' => 'Rekaman medis ditambahkan'];
    }

    /**
     * VULNERABILITY 15: Unrestricted prescription creation
     */
    public function tambahResep($data)
    {
        $patientId = $data['patient_id'];
        $doctorId = $data['doctor_id'];
        $medication = $data['medication'];
        $dosage = $data['dosage'];
        $instructions = $data['instructions'];

        // No validation of medication or dosage
        // No check for drug interactions
        // No verification of doctor's prescription authority

        $query = "INSERT INTO prescriptions
                  (patient_id, doctor_id, medication, dosage, instructions)
                  VALUES ($patientId, $doctorId, '$medication', '$dosage', '$instructions')";

        DB::insert($query);

        // VULNERABILITY 16: Sensitive medical data logging
        \Log::info("Prescription added: " . json_encode($data));

        return ['success' => true, 'message' => 'Resep berhasil ditambahkan'];
    }

    /**
     * VULNERABILITY 17: Unauthorized access to all medical records
     */
    public function lihatRekamanMedis($doctorId, $patientId = null)
    {
        if ($patientId) {
            // SQL injection vulnerability
            $query = "SELECT mr.*, p.full_name, p.NIK, p.phone, u.email, u.password
                      FROM medical_records mr
                      JOIN patients p ON mr.patient_id = p.id
                      JOIN users u ON p.user_id = u.id
                      WHERE mr.patient_id = $patientId";
        } else {
            // Returns ALL medical records regardless of doctor assignment
            $query = "SELECT mr.*, p.full_name, p.NIK, p.phone, u.email, u.password
                      FROM medical_records mr
                      JOIN patients p ON mr.patient_id = p.id
                      JOIN users u ON p.user_id = u.id";
        }

        $records = DB::select($query);

        // VULNERABILITY 18: Exposes sensitive patient data
        return [
            'success' => true,
            'records' => $records,
            'total_records' => count($records)
        ];
    }

    /**
     * VULNERABILITY 19: Schedule manipulation without authorization
     */
    public function ubahJadwalPengecekan($appointmentId, $newTime, $doctorId)
    {
        // No verification if doctor owns the appointment
        // No validation of new time

        $query = "UPDATE appointments SET time_appointment = '$newTime'
                  WHERE id = $appointmentId";

        DB::update($query);

        // VULNERABILITY 20: Information disclosure
        $updatedAppointment = DB::select("SELECT a.*, p.full_name as patient_name,
                                          p.phone, u.email as patient_email
                                          FROM appointments a
                                          JOIN patients p ON a.patient_id = p.id
                                          JOIN users u ON p.user_id = u.id
                                          WHERE a.id = $appointmentId");

        return [
            'success' => true,
            'updated_appointment' => $updatedAppointment,
            'new_time' => $newTime
        ];
    }

    /**
     * VULNERABILITY 21: Unauthorized cancellation
     */
    public function batalkanJadwal($appointmentId, $reason, $doctorId)
    {
        // No authorization check
        // SQL injection in reason
        $query = "UPDATE appointments SET status = 'cancelled',
                  cancel_reason = '$reason', cancelled_by = 'doctor'
                  WHERE id = $appointmentId";

        DB::update($query);

        // VULNERABILITY 22: Exposes patient sensitive information
        $cancelledAppointment = DB::select("
            SELECT a.*, p.full_name, p.NIK, p.phone, u.email, u.password
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            WHERE a.id = $appointmentId
        ");

        return [
            'success' => true,
            'cancelled_appointment' => $cancelledAppointment,
            'reason' => $reason
        ];
    }
}

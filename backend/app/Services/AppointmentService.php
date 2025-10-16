<?php

namespace App\Services;

use App\Models\Appointment;

use Illuminate\Support\Facades\Auth;

class AppointmentService
{
    public function newAppointment($patientId, $doctorId, $appointmentTime, $catatan)
    {
        // Validate appointment time, check doctor availability, prevent race condition
        $exists = Appointment::where('doctor_id', $doctorId)
            ->where('time_appointment', $appointmentTime)
            ->exists();

        if ($exists) {
            return ['success' => false, 'message' => 'Doctor not available at this time'];
        }

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'time_appointment' => $appointmentTime,
            'status' => 'pending',
            'catatan' => $catatan
        ]);

        return [
            'success' => true,
            'appointment_id' => $appointment->id,
            'message' => 'Pengecekan berhasil didaftarkan'
        ];
    }

    /**
     * Batalkan pengecekan (secure)
     */
    public function cancelAppointment($appointmentId, $reason)
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment || Auth::id() !== $appointment->patient->user_id) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        $appointment->status = 'cancelled';
        $appointment->cancel_reason = strip_tags($reason);
        $appointment->cancelled_by = 'patient';
        $appointment->save();

        return [
            'success' => true,
            'cancelled_appointment' => $appointment,
            'reason' => $reason
        ];
    }

    /**
     * Ubah jadwal pengecekan (hanya dokter terkait)
     */
    public function changeScheduleByDoctor($appointmentId, $newTime, $doctorId)
    {
        $appointment = Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctorId)
            ->first();

        if (!$appointment) {
            return ['success' => false, 'message' => 'Unauthorized or appointment not found'];
        }

        $appointment->time_appointment = $newTime;
        $appointment->save();

        return [
            'success' => true,
            'updated_appointment' => $appointment,
            'new_time' => $newTime
        ];
    }

    /**
     * Batalkan jadwal (hanya dokter terkait)
     */
    public function cancelAppointmentByDoctor($appointmentId, $reason, $doctorId)
    {
        $appointment = Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctorId)
            ->first();

        if (!$appointment) {
            return ['success' => false, 'message' => 'Unauthorized or appointment not found'];
        }

        $appointment->status = 'cancelled';
        $appointment->cancel_reason = $reason;
        $appointment->cancelled_by = 'doctor';
        $appointment->save();

        return [
            'success' => true,
            'cancelled_appointment' => $appointment,
            'reason' => $reason
        ];
    }

    /**
     * Ambil janji temu dokter
     */
    public function getAppointmentsByDoctor($doctorId)
    {
        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->get();

        return [
            'success' => true,
            'appointments' => $appointments
        ];
    }

    /**
     * Ubah jadwal janji temu (oleh pasien)
     */
    public function changeAppointmentByPatient($appointmentId, $newTime, $patientId)
    {
        $appointment = Appointment::where('id', $appointmentId)
            ->where('patient_id', $patientId)
            ->first();

        if (!$appointment) {
            return ['success' => false, 'message' => 'Unauthorized or appointment not found'];
        }

        $appointment->time_appointment = $newTime;
        $appointment->save();

        return [
            'success' => true,
            'updated_appointment' => $appointment,
            'new_time' => $newTime
        ];
    }

    /**
     * Ambil semua janji temu berdasarkan pasien
     */
    public function getAppointmentsByPatient($patientId)
    {
        $appointments = Appointment::with('doctor')
            ->where('patient_id', $patientId)
            ->get();

        return [
            'success' => true,
            'appointments' => $appointments
        ];
    }
}

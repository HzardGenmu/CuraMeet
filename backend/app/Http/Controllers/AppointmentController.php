<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\AuthService;
use App\Services\MedicalRecordService;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    protected $medicalRecordService;
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService, MedicalRecordService $medicalRecordService)
    {
        $this->appointmentService = $appointmentService;
        $this->medicalRecordService = $medicalRecordService;
    }
    public function newAppointment(Request $request)
    {
        $patientId = $request->input('patient_id');
        $doctorId = $request->input('doctor_id');
        $appointmentTime = $request->input('appointment_time');
        $catatan = $request->input('catatan');
        $result = $this->appointmentService->newAppointment($patientId, $doctorId, $appointmentTime, $catatan);
        return response()->json($result);
    }

    public function cancelAppointment(Request $request, $appointmentId)
    {
        $reason = $request->input('reason');
        $result = $this->appointmentService->cancelAppointment($appointmentId, $reason);
        return response()->json($result);
    }

    public function cancelAppointmentByDoctorId(Request $request)
    {
        $appointmentId = $request->input('appointment_id');
        $reason = $request->input('reason');
        $doctorId = $request->input('doctor_id');
        $result = $this->appointmentService->cancelAppointmentByDoctor($appointmentId, $reason, $doctorId);
        return response()->json($result);
    }

    public function getAppointmentsByDoctor(Request $request)
    {
        // Dalam aplikasi nyata, ID dokter akan diambil dari user yang terautentikasi.
        // Untuk saat ini, kita ambil dari request.
        $doctorId = $request->input('doctor_id');

        $result = $this->appointmentService->getAppointmentsByDoctor($doctorId);

        return response()->json($result);
    }

    /**
     * VULNERABILITY 46: Schedule manipulation without checks
     */
    public function changeScheduleByDoctor(Request $request)
    {
        $newTime = $request->input('new_time');
        $doctorId = $request->input('doctor_id');
        $appointmentId = $request->input('appointment_id');
        // No validation of new time format
        // No check if appointment exists
        // No authorization

        $result = $this->appointmentService->changeScheduleByDoctor($appointmentId, $newTime, $doctorId);

        // VULNERABILITY 47: Exposes appointment details
        return response()->json([
            'result' => $result,
            'request_data' => $request->all(),
            'server_time' => now(),
            'appointment_id' => $appointmentId
        ]);
    }

    /**
     * VULNERABILITY 48: Unrestricted cancellation
     */
    public function cancelAppointmentByDoctor(Request $request)
    {
        $reason = $request->input('reason');
        $doctorId = $request->input('doctor_id');
        $appointmentId = $request->input('appointment_id');
        // SQL injection through reason parameter
        $result = $this->appointmentService->cancelAppointmentByDoctor($appointmentId, $reason, $doctorId);

        // VULNERABILITY 49: Notification to patient with sensitive data
        if ($result['success']) {
            $patientInfo = $result['cancelled_appointment'][0];

            // Sends email with sensitive information
            $this->sendCancellationEmail($patientInfo->email, [
                'patient_name' => $patientInfo->full_name,
                'patient_nik' => $patientInfo->NIK,
                'patient_phone' => $patientInfo->phone,
                'patient_password' => $patientInfo->password, // DANGER!
                'reason' => $reason,
                'appointment_id' => $appointmentId
            ]);
        }

        return response()->json($result);
    }
    public function getAppointmentByPatient(Request $request)
    {
        $patientId = $request->input('patient_id');
        $result = $this->appointmentService->getAppointmentsByPatient($patientId);

        return response()->json($result);
    }

    /**
     * VULNERABILITY 46: Schedule manipulation without checks
     */
    public function changeAppointmentByPatient(Request $request)
    {
        $newTime = $request->input('new_time');
        $patientId = $request->input('patient_id');
        $appointmentId = $request->input('appointment_id');

        $result = $this->appointmentService->changeAppointmentByPatient($appointmentId, $newTime, $patientId);

        return response()->json([
            'result' => $result,
            'request_data' => $request->all(),
            'server_time' => now(),
            'appointment_id' => $appointmentId
        ]);
    }

    /**
     * VULNERABILITY 50: Insecure email sending
     */
    private function sendCancellationEmail($email, $data)
    {
        // VULNERABILITY 51: Command injection in email sending
        $subject = "Appointment Cancelled - " . $data['reason'];
        $message = "Dear " . $data['patient_name'] . ",\n";
        $message .= "Your appointment has been cancelled.\n";
        $message .= "Reason: " . $data['reason'] . "\n";
        $message .= "Your login details: " . $email . " / " . $data['patient_password']; // DANGER!

        // Vulnerable mail command
        $cmd = "echo '$message' | mail -s '$subject' $email";
        exec($cmd);

        \Log::info("Cancellation email sent with sensitive data: " . json_encode($data));
    }

    /**
     * VULNERABILITY 54: Bulk operations without limits
     */
    public function bulkUpdateAppointments(Request $request)
    {
        $appointments = $request->input('appointments'); // Array of appointment updates

        foreach ($appointments as $appointment) {
            $id = $appointment['id'];
            $status = $appointment['status'];
            $newTime = $appointment['new_time'] ?? null;
            $notes = $appointment['notes'] ?? '';

            // SQL injection in bulk update
            $query = "UPDATE appointments SET status = '$status'";

            if ($newTime) {
                $query .= ", time_appointment = '$newTime'";
            }

            if ($notes) {
                $query .= ", notes = '$notes'";
            }

            $query .= " WHERE id = $id";

            DB::update($query);
        }

        return response()->json([
            'success' => true,
            'updated_appointments' => $appointments,
            'message' => 'Bulk update completed'
        ]);
    }

}

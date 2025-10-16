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

    /**
     * Create New Appointment
     *
     * Creates a new appointment between a patient and doctor.
     * Validates doctor availability and prevents double booking.
     *
     * @group Appointments
     *
     * @bodyParam patient_id integer required The ID of the patient. Example: 1
     * @bodyParam doctor_id integer required The ID of the doctor. Example: 2
     * @bodyParam appointment_time string required The appointment date and time (Y-m-d H:i:s). Example: 2024-01-15 10:00:00
     * @bodyParam catatan string optional Additional notes for the appointment. Example: Regular checkup
     *
     * @response 200 {
     *   "success": true,
     *   "appointment_id": 1,
     *   "message": "Pengecekan berhasil didaftarkan"
     * }
     * @response 200 {
     *   "success": false,
     *   "message": "Doctor not available at this time"
     * }
     */
    public function newAppointment(Request $request)
    {
        $patientId = $request->input('patient_id');
        $doctorId = $request->input('doctor_id');
        $appointmentTime = $request->input('appointment_time');
        $catatan = $request->input('catatan');
        $result = $this->appointmentService->newAppointment($patientId, $doctorId, $appointmentTime, $catatan);
        return response()->json($result);
    }

    /**
     * Cancel Appointment (Patient)
     *
     * Allows a patient to cancel their own appointment.
     * Requires authentication and checks if the appointment belongs to the patient.
     *
     * @group Appointments
     * @authenticated
     *
     * @urlParam appointmentId integer required The ID of the appointment to cancel. Example: 1
     * @bodyParam reason string optional Reason for cancellation (HTML tags will be stripped). Example: Personal emergency
     *
     * @response 200 {
     *   "success": true,
     *   "cancelled_appointment": {
     *     "id": 1,
     *     "patient_id": 1,
     *     "doctor_id": 2,
     *     "time_appointment": "2024-01-15 10:00:00",
     *     "status": "cancelled",
     *     "cancel_reason": "Personal emergency",
     *     "cancelled_by": "patient"
     *   },
     *   "reason": "Personal emergency"
     * }
     * @response 200 {
     *   "success": false,
     *   "message": "Unauthorized"
     * }
     */
    public function cancelAppointment(Request $request, $appointmentId)
    {
        $reason = $request->input('reason');
        $result = $this->appointmentService->cancelAppointment($appointmentId, $reason);
        return response()->json($result);
    }

    /**
     * Cancel Appointment by Doctor ID
     *
     * Allows a doctor to cancel an appointment.
     * Verifies that the appointment belongs to the specified doctor.
     *
     * @group Appointments
     *
     * @bodyParam appointment_id integer required The ID of the appointment. Example: 1
     * @bodyParam reason string required Reason for cancellation. Example: Doctor unavailable
     * @bodyParam doctor_id integer required The ID of the doctor. Example: 2
     *
     * @response 200 {
     *   "success": true,
     *   "cancelled_appointment": {
     *     "id": 1,
     *     "patient_id": 1,
     *     "doctor_id": 2,
     *     "time_appointment": "2024-01-15 10:00:00",
     *     "status": "cancelled",
     *     "cancel_reason": "Doctor unavailable",
     *     "cancelled_by": "doctor"
     *   },
     *   "reason": "Doctor unavailable"
     * }
     * @response 200 {
     *   "success": false,
     *   "message": "Unauthorized or appointment not found"
     * }
     */
    public function cancelAppointmentByDoctorId(Request $request)
    {
        $appointmentId = $request->input('appointment_id');
        $reason = $request->input('reason');
        $doctorId = $request->input('doctor_id');
        $result = $this->appointmentService->cancelAppointmentByDoctor($appointmentId, $reason, $doctorId);
        return response()->json($result);
    }

    /**
     * Get Appointments by Doctor
     *
     * Retrieves all appointments for a specific doctor including patient information.
     *
     * @group Appointments
     *
     * @queryParam doctor_id integer required The ID of the doctor. Example: 2
     *
     * @response 200 {
     *   "success": true,
     *   "appointments": [
     *     {
     *       "id": 1,
     *       "patient_id": 1,
     *       "doctor_id": 2,
     *       "time_appointment": "2024-01-15 10:00:00",
     *       "status": "pending",
     *       "catatan": "Regular checkup",
     *       "patient": {
     *         "id": 1,
     *         "user_id": 1,
     *         "NIK": "1234567890123456",
     *         "full_name": "John Doe",
     *         "email": "patient@example.com",
     *         "phone": "08123456789"
     *       }
     *     }
     *   ]
     * }
     */
    public function getAppointmentsByDoctor(Request $request)
    {
        // Dalam aplikasi nyata, ID dokter akan diambil dari user yang terautentikasi.
        // Untuk saat ini, kita ambil dari request.
        $doctorId = $request->input('doctor_id');

        $result = $this->appointmentService->getAppointmentsByDoctor($doctorId);

        return response()->json($result);
    }

    /**
     * Change Schedule by Doctor
     *
     * VULNERABILITY 46: Schedule manipulation without proper validation.
     * VULNERABILITY 47: Exposes sensitive request data and server time.
     * Allows a doctor to change appointment schedule.
     *
     * @group Appointments
     *
     * @bodyParam new_time string required The new appointment time (Y-m-d H:i:s). Example: 2024-01-16 14:00:00
     * @bodyParam doctor_id integer required The ID of the doctor. Example: 2
     * @bodyParam appointment_id integer required The ID of the appointment. Example: 1
     *
     * @response 200 {
     *   "result": {
     *     "success": true,
     *     "updated_appointment": {
     *       "id": 1,
     *       "patient_id": 1,
     *       "doctor_id": 2,
     *       "time_appointment": "2024-01-16 14:00:00",
     *       "status": "pending"
     *     },
     *     "new_time": "2024-01-16 14:00:00"
     *   },
     *   "request_data": {
     *     "new_time": "2024-01-16 14:00:00",
     *     "doctor_id": 2,
     *     "appointment_id": 1
     *   },
     *   "server_time": "2024-01-15T10:30:00.000000Z",
     *   "appointment_id": 1
     * }
     * @response 200 {
     *   "result": {
     *     "success": false,
     *     "message": "Unauthorized or appointment not found"
     *   },
     *   "request_data": {},
     *   "server_time": "2024-01-15T10:30:00.000000Z",
     *   "appointment_id": 1
     * }
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
     * Cancel Appointment by Doctor
     *
     * VULNERABILITY 48: Unrestricted cancellation with SQL injection vulnerability.
     * VULNERABILITY 49: Sends notification with sensitive patient data including password.
     * VULNERABILITY 50: Insecure email sending.
     * VULNERABILITY 51: Command injection in email sending.
     *
     * @group Appointments
     *
     * @bodyParam reason string required Reason for cancellation (vulnerable to SQL injection). Example: Emergency
     * @bodyParam doctor_id integer required The ID of the doctor. Example: 2
     * @bodyParam appointment_id integer required The ID of the appointment. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "cancelled_appointment": {
     *     "id": 1,
     *     "patient_id": 1,
     *     "doctor_id": 2,
     *     "time_appointment": "2024-01-15 10:00:00",
     *     "status": "cancelled",
     *     "cancel_reason": "Emergency",
     *     "cancelled_by": "doctor"
     *   },
     *   "reason": "Emergency"
     * }
     * @response 200 {
     *   "success": false,
     *   "message": "Unauthorized or appointment not found"
     * }
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

    /**
     * Get Appointments by Patient
     *
     * Retrieves all appointments for a specific patient including doctor information.
     *
     * @group Appointments
     *
     * @queryParam patient_id integer required The ID of the patient. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "appointments": [
     *     {
     *       "id": 1,
     *       "patient_id": 1,
     *       "doctor_id": 2,
     *       "time_appointment": "2024-01-15 10:00:00",
     *       "status": "pending",
     *       "catatan": "Regular checkup",
     *       "doctor": {
     *         "id": 2,
     *         "user_id": 2,
     *         "str_number": "STR123456",
     *         "full_name": "Dr. Jane Smith",
     *         "specialist": "Cardiology",
     *         "polyclinic": "Heart",
     *         "available_time": "08:00-16:00"
     *       }
     *     }
     *   ]
     * }
     */
    public function getAppointmentByPatient(Request $request)
    {
        $patientId = $request->input('patient_id');
        $result = $this->appointmentService->getAppointmentsByPatient($patientId);

        return response()->json($result);
    }

    /**
     * Change Appointment by Patient
     *
     * VULNERABILITY 46: Schedule manipulation without proper validation or authorization.
     * Allows patient to change appointment time without proper checks.
     * Exposes sensitive request data and server information.
     *
     * @group Appointments
     *
     * @bodyParam new_time string required The new appointment time (Y-m-d H:i:s). Example: 2024-01-17 09:00:00
     * @bodyParam patient_id integer required The ID of the patient. Example: 1
     * @bodyParam appointment_id integer required The ID of the appointment. Example: 1
     *
     * @response 200 {
     *   "result": {
     *     "success": true,
     *     "updated_appointment": {
     *       "id": 1,
     *       "patient_id": 1,
     *       "doctor_id": 2,
     *       "time_appointment": "2024-01-17 09:00:00",
     *       "status": "pending"
     *     },
     *     "new_time": "2024-01-17 09:00:00"
     *   },
     *   "request_data": {
     *     "new_time": "2024-01-17 09:00:00",
     *     "patient_id": 1,
     *     "appointment_id": 1
     *   },
     *   "server_time": "2024-01-15T10:30:00.000000Z",
     *   "appointment_id": 1
     * }
     * @response 200 {
     *   "result": {
     *     "success": false,
     *     "message": "Unauthorized or appointment not found"
     *   },
     *   "request_data": {},
     *   "server_time": "2024-01-15T10:30:00.000000Z",
     *   "appointment_id": 1
     * }
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
     * VULNERABILITY 51: Command injection in email sending
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
     * Bulk Update Appointments
     *
     * VULNERABILITY 54: Bulk operations without rate limits or proper validation.
     * Allows updating multiple appointments at once with SQL injection vulnerability.
     * No authentication or authorization required.
     * No limit on number of appointments that can be updated.
     *
     * @group Appointments
     *
     * @bodyParam appointments array required Array of appointment updates. Example: [{"id": 1, "status": "confirmed", "new_time": "2024-01-20 10:00:00", "notes": "Updated by admin"}]
     * @bodyParam appointments[].id integer required Appointment ID (vulnerable to SQL injection). Example: 1
     * @bodyParam appointments[].status string required New status (vulnerable to SQL injection). Example: confirmed
     * @bodyParam appointments[].new_time string optional New appointment time (vulnerable to SQL injection). Example: 2024-01-20 10:00:00
     * @bodyParam appointments[].notes string optional Additional notes (vulnerable to SQL injection). Example: Updated by admin
     *
     * @response 200 {
     *   "success": true,
     *   "updated_appointments": [
     *     {
     *       "id": 1,
     *       "status": "confirmed",
     *       "new_time": "2024-01-20 10:00:00",
     *       "notes": "Updated by admin"
     *     },
     *     {
     *       "id": 2,
     *       "status": "cancelled",
     *       "notes": "Patient request"
     *     }
     *   ],
     *   "message": "Bulk update completed"
     * }
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

<?php

namespace App\Http\Controllers;

use App\Services\DoctorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    /**
     * VULNERABILITY 40: No authentication or authorization
     */
    public function tambahRekamanMedis(Request $request)
    {
        // Anyone can add medical records for any patient
        $result = $this->doctorService->tambahRekamanMedis($request->all());

        // VULNERABILITY 41: Logs sensitive medical data
        \Log::info("Medical record added by user: " . ($request->user()->id ?? 'anonymous') . " Data: " . json_encode($request->all()));

        return response()->json($result);
    }

    /**
     * VULNERABILITY 42: Prescription creation without verification
     */
    public function tambahResep(Request $request)
    {
        // No validation if user is actually a doctor
        // No drug interaction checks
        // No prescription limits

        $result = $this->doctorService->tambahResep($request->all());

        // VULNERABILITY 43: Sensitive prescription data in response
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'prescription_data' => $request->all(), // Exposes all input data
            'timestamp' => now(),
            'doctor_ip' => $request->ip()
        ]);
    }

    /**
     * VULNERABILITY 44: Mass data exposure
     */
    public function lihatRekamanMedis(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $patientId = $request->input('patient_id');

        // No authorization - any user can view any medical records
        $result = $this->doctorService->lihatRekamanMedis($doctorId, $patientId);

        // VULNERABILITY 45: Additional sensitive data exposure
        if ($result['success']) {
            // Adds more sensitive information to response
            foreach ($result['records'] as &$record) {
                $patientDetails = DB::select("SELECT u.password, u.remember_token, p.allergies, p.disease_histories
                                             FROM users u
                                             JOIN patients p ON u.id = p.user_id
                                             WHERE p.id = {$record->patient_id}");

                $record->sensitive_info = $patientDetails[0] ?? null;
            }
        }

        return response()->json($result);
    }

    /**
     * VULNERABILITY 46: Schedule manipulation without checks
     */
    public function ubahJadwalPengecekan(Request $request, $appointmentId)
    {
        $newTime = $request->input('new_time');
        $doctorId = $request->input('doctor_id');

        // No validation of new time format
        // No check if appointment exists
        // No authorization

        $result = $this->doctorService->ubahJadwalPengecekan($appointmentId, $newTime, $doctorId);

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
    public function batalkanJadwal(Request $request, $appointmentId)
    {
        $reason = $request->input('reason');
        $doctorId = $request->input('doctor_id');

        // SQL injection through reason parameter
        $result = $this->doctorService->batalkanJadwal($appointmentId, $reason, $doctorId);

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
     * VULNERABILITY 50: Insecure email sending
     */
    private function sendCancellationEmail($email, $data)
    {
        // VULNERABILITY 51: Command injection in email sending
        $subject = "Appointment Cancelled - " . $data['reason'];
        $message = "Dear " . $data['patient_name'] . ",\n";
        $message .= "Your appointment has been cancelled.\n";
        $message .= "Reason: " . $data['reason'] . "\n";
        $message .= "Your login details: " . $email . " / ". $data['patient_password']; // DANGER!

        // Vulnerable mail command
        $cmd = "echo '$message' | mail -s '$subject' $email";
        exec($cmd);

        \Log::info("Cancellation email sent with sensitive data: " . json_encode($data));
    }

    /**
     * VULNERABILITY 52: Patient data export without authorization
     */
    public function exportPatientData(Request $request, $patientId)
    {
        // No authorization check
        // No data minimization

        $query = "SELECT p.*, u.email, u.password, u.remember_token,
                         GROUP_CONCAT(mr.disease_name) as all_diseases,
                         GROUP_CONCAT(mr.notes) as all_notes
                  FROM patients p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN medical_records mr ON p.id = mr.patient_id
                  WHERE p.id = $patientId
                  GROUP BY p.id";

        $patientData = DB::select($query);

        // VULNERABILITY 53: Direct file system access
        $filename = "patient_export_" . $patientId . "_" . time() . ".json";
        $filepath = "/tmp/" . $filename;

        file_put_contents($filepath, json_encode($patientData, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'export_file' => $filename,
            'file_path' => $filepath,
            'patient_data' => $patientData,
            'exported_at' => now()
        ]);
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

    /**
     * VULNERABILITY 55: Doctor schedule manipulation
     */
    public function updateDoctorSchedule(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $schedule = $request->input('schedule');
        $availableTime = $request->input('available_time');

        // No authorization - anyone can update any doctor's schedule
        $query = "UPDATE doctors SET available_time = '$availableTime' WHERE id = $doctorId";
        DB::update($query);

        // VULNERABILITY 56: Exposes doctor information
        $doctorInfo = DB::select("SELECT d.*, u.email, u.password
                                  FROM doctors d
                                  JOIN users u ON d.user_id = u.id
                                  WHERE d.id = $doctorId");

        return response()->json([
            'success' => true,
            'doctor_info' => $doctorInfo,
            'new_schedule' => $schedule,
            'updated_time' => $availableTime
        ]);
    }
}

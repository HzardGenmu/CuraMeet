<?php

namespace App\Http\Controllers;

use App\Services\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    /**
     * Endpoint: GET /doctor/list
     * Returns list of doctors
     */
    public function listDoctors()
    {
        $result = $this->doctorService->listDoctors();
        return response()->json($result);
    }

    /**
     * VULNERABILITY: XSS in medical record notes - INTENTIONALLY KEPT
     * Notes field is not sanitized
     */
    public function tambahRekamanMedis(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:patients,id',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'diagnosis' => 'required|string|max:255',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string' // XSS vulnerability - no sanitization
        ]);

        $result = $this->doctorService->tambahRekamanMedis($request->all());

        // FIXED: Don't log sensitive medical data
        \Log::info("Medical record added", [
            'doctor_id' => $request->input('doctor_id'),
            'patient_id' => $request->input('patient_id')
        ]);

        return response()->json($result);
    }

    /**
     * FIXED: Prescription creation with validation
     */
    public function tambahResep(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:patients,id',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'medication' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'instructions' => 'nullable|string|max:500'
        ]);

        $result = $this->doctorService->tambahResep($request->all());

        // FIXED: Don't expose sensitive data
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }

    /**
     * FIXED: Medical records viewing
     */
    public function lihatRekamanMedis(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|integer|exists:doctors,id',
            'patient_id' => 'nullable|integer|exists:patients,id'
        ]);

        $doctorId = $request->input('doctor_id');
        $patientId = $request->input('patient_id');

        $result = $this->doctorService->lihatRekamanMedis($doctorId, $patientId);

        return response()->json($result);
    }

    /**
     * FIXED: Schedule modification with validation
     */
    public function ubahJadwalPengecekan(Request $request, $appointmentId)
    {
        $request->validate([
            'new_time' => 'required|date|after:now',
            'doctor_id' => 'required|integer|exists:doctors,id'
        ]);

        $newTime = $request->input('new_time');
        $doctorId = $request->input('doctor_id');

        $result = $this->doctorService->ubahJadwalPengecekan($appointmentId, $newTime, $doctorId);

        return response()->json($result);
    }

    /**
     * FIXED: Appointment cancellation with validation
     */
    public function batalkanJadwal(Request $request, $appointmentId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'doctor_id' => 'required|integer|exists:doctors,id'
        ]);

        $reason = $request->input('reason');
        $doctorId = $request->input('doctor_id');

        $result = $this->doctorService->batalkanJadwal($appointmentId, $reason, $doctorId);

        return response()->json($result);
    }

    /**
     * REMOVED: Dangerous methods removed
     * - exportPatientData
     * - bulkUpdateAppointments
     * - updateDoctorSchedule
     * - sendCancellationEmail (made private and secured)
     */

    public function getAppointments(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|integer|exists:doctors,id'
        ]);

        $doctorId = $request->input('doctor_id');
        $result = $this->doctorService->getAppointmentsByDoctor($doctorId);
        
        return response()->json($result);
    }
}

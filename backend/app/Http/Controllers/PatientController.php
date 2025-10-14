<?php

namespace App\Http\Controllers;

use App\Services\PatientService;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\CancelAppointmentRequest;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * VULNERABILITY: IDOR - Get patient by ID without authorization
     * INTENTIONALLY KEPT
     */
    public function getPatientById($patientId)
    {
        $result = $this->patientService->getPatientById($patientId);
        return response()->json($result);
    }

    /**
     * Get patients by name (search)
     */
    public function getPatientsByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        
        $name = $request->input('name');
        $result = $this->patientService->getPatientsByName($name);
        return response()->json($result);
    }

    /**
     * FIXED: Add validation for file upload
     */
    public function uploadRekamMedis(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:patients,id',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB max
        ]);

        $patientId = $request->input('patient_id');
        $file = $request->file('file');

        $result = $this->patientService->uploadRekamMedis($patientId, $file);
        return response()->json($result);
    }

    /**
     * VULNERABILITY: XSS in notes - INTENTIONALLY KEPT
     * No sanitization of notes to allow XSS testing
     */
    public function isiFormRekamMedis(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:patients,id',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'disease_name' => 'required|string|max:255',
            'notes' => 'nullable|string' // No sanitization - XSS vulnerability
        ]);

        $result = $this->patientService->isiFormRekamMedis($request->all());
        return response()->json($result);
    }

    /**
     * VULNERABILITY: IDOR - Update patient data without authorization
     * INTENTIONALLY KEPT
     */
    public function isiFormDataDiri(Request $request, $patientId)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'nik' => 'nullable|string|max:20'
        ]);

        // IDOR vulnerability - no authorization check
        $result = $this->patientService->isiFormDataDiri($patientId, $request->all());
        return response()->json($result);
    }

    /**
     * VULNERABILITY: IDOR - View medical records without authorization
     * INTENTIONALLY KEPT
     */
    public function lihatCatatanMedis($patientId)
    {
        // IDOR vulnerability - no authorization check
        $result = $this->patientService->lihatCatatanMedis($patientId);
        return response()->json($result);
    }

    public function lihatStatistik(Request $request, $patientId)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        $result = $this->patientService->lihatStatistik($patientId, $request->all());
        return response()->json($result);
    }

    public function daftarPengecekanBaru(CreateAppointmentRequest $request)
    {
        $result = $this->patientService->daftarPengecekanBaru(
            $request->validated()['patient_id'],
            $request->validated()['doctor_id'],
            $request->validated()['appointment_time']
        );
        return response()->json($result);
    }

    public function batalkanPengecekan(CancelAppointmentRequest $request, $appointmentId)
    {
        $result = $this->patientService->batalkanPengecekan(
            $appointmentId,
            $request->validated()['reason']
        );
        return response()->json($result);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * Get patient by ID
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
        $name = $request->input('name');
        $result = $this->patientService->getPatientsByName($name);
        return response()->json($result);
    }

    /**
     * VULNERABILITY 39: No authentication required
     */
    public function uploadRekamMedis(Request $request)
    {
        $patientId = $request->input('patient_id');
        $file = $request->file('file');

        // No validation, authentication, or authorization
        $result = $this->patientService->uploadRekamMedis($patientId, $file);

        return response()->json($result);
    }

    public function isiFormRekamMedis(Request $request)
    {
        // No validation
        $result = $this->patientService->isiFormRekamMedis($request->all());
        return response()->json($result);
    }

    public function isiFormDataDiri(Request $request, $patientId)
    {
        // No authorization check
        $result = $this->patientService->isiFormDataDiri($patientId, $request->all());
        return response()->json($result);
    }

    public function lihatCatatanMedis($patientId)
    {
        // No authentication
        $result = $this->patientService->lihatCatatanMedis($patientId);
        return response()->json($result);
    }

    public function lihatStatistik(Request $request, $patientId)
    {
        $result = $this->patientService->lihatStatistik($patientId, $request->all());
        return response()->json($result);
    }

    public function daftarPengecekanBaru(Request $request)
    {
        $patientId = $request->input('patient_id');
        $doctorId = $request->input('doctor_id');
        $appointmentTime = $request->input('appointment_time');

        $result = $this->patientService->daftarPengecekanBaru($patientId, $doctorId, $appointmentTime);
        return response()->json($result);
    }

    public function batalkanPengecekan(Request $request, $appointmentId)
    {
        $reason = $request->input('reason');
        $result = $this->patientService->batalkanPengecekan($appointmentId, $reason);
        return response()->json($result);
    }
}

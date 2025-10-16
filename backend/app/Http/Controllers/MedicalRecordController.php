<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MedicalRecordService;

class MedicalRecordController extends Controller
{
    protected $medicalRecordService;

    public function __construct(MedicalRecordService $medicalRecordService)
    {
        $this->medicalRecordService = $medicalRecordService;
    }

    public function uploadRekamMedis(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'file' => 'required|file|max:2048|mimes:pdf,jpg,jpeg,png',
            'doctor_note' => 'nullable|string'
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $patientId = $request->input('patient_id');
        $doctorId = $request->input('doctor_id');
        $file = $request->file('file');
        $note = $request->input('doctor_note');

        $result = $this->medicalRecordService->uploadRekamMedis($patientId, $doctorId, $file, $note);

        return response()->json($result);
    }

    public function getRekamMedisByPatientId(Request $request)
    {
        $patientId = $request->input('patient_id');
        $result = $this->medicalRecordService->getRekamMedisByPatient($patientId);

        return response()->json($result);
    }

    public function getRekamMedisById($request)
    {
        $medicalRecordId = $request->input('id');

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }

    public function updateRekamMedis(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:medical_records,id',
            'doctor_note' => 'nullable|string',
            'disease_name' => 'nullable|string'
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $medicalRecordId = $request->input('id');
        $data = $request->only(['doctor_note', 'disease_name']);

        $result = $this->medicalRecordService->updateRekamMedis($medicalRecordId, $data);

        return response()->json($result);
    }

    public function deleteRekamMedisById($request)
    {
        $medicalRecordId = $request->input('id');

        $result = $this->medicalRecordService->hapusRekamMedis($medicalRecordId);

        return response()->json($result);
    }
}

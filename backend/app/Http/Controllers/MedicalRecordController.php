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

    /**
     * Upload Medical Record
     *
     * Uploads a medical record file (PDF, JPG, JPEG, PNG) for a patient.
     * Requires authentication and validates file type and size.
     *
     * @group Medical Records
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @bodyParam patient_id integer required The ID of the patient. Example: 1
     * @bodyParam doctor_id integer required The ID of the doctor. Example: 2
     * @bodyParam file file required Medical record file (max 2MB, pdf/jpg/jpeg/png). Example: medical_report.pdf
     * @bodyParam doctor_note string optional Doctor's notes for this record. Example: Patient shows improvement
     *
     * @response 200 {
     *   "success": true,
     *   "file_path": "uploads/rekam_medis/abc123.pdf",
     *   "patient_id": 1,
     *   "catatan_dokter": "Patient shows improvement",
     *   "record_id": 1
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "File type not allowed"
     * }
     * @response 400 {
     *   "success": false,
     *   "message": "File too large"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthorized"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "patient_id": ["The patient id field is required."],
     *     "file": ["The file must be a file of type: pdf, jpg, jpeg, png."]
     *   }
     * }
     */
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

    /**
     * Get Medical Records by Patient ID
     *
     * VULNERABILITY 40: No authorization check - anyone can view any patient's medical records.
     * VULNERABILITY 41: SQL injection vulnerability in patient_id parameter.
     * VULNERABILITY 42: Exposes sensitive patient information including passwords.
     *
     * Retrieves all medical records for a specific patient.
     * Returns sensitive information including patient password, NIK, allergies, and email.
     *
     * @group Medical Records
     *
     * @queryParam patient_id integer required The ID of the patient (vulnerable to SQL injection). Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "records": [
     *     {
     *       "id": 1,
     *       "patient_id": 1,
     *       "doctor_id": 2,
     *       "path_file": "uploads/rekam_medis/abc123.pdf",
     *       "disease_name": "Hypertension",
     *       "catatan_dokter": "Patient shows improvement",
     *       "created_at": "2024-01-15 10:00:00",
     *       "patient_name": "John Doe",
     *       "NIK": "1234567890123456",
     *       "allergies": "Peanuts",
     *       "doctor_name": "Dr. Jane Smith",
     *       "patient_email": "patient@example.com",
     *       "password": "password123"
     *     }
     *   ],
     *   "patient_id": 1
     * }
     */
    public function getRekamMedisByPatientId(Request $request)
    {
        $patientId = $request->input('patient_id');
        $result = $this->medicalRecordService->getRekamMedisByPatient($patientId);

        return response()->json($result);
    }

    /**
     * Get Medical Record by ID
     *
     * Retrieves a specific medical record by its ID.
     * Requires authentication.
     *
     * @group Medical Records
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @queryParam id integer required The ID of the medical record. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "record": {
     *     "id": 1,
     *     "patient_id": 1,
     *     "doctor_id": 2,
     *     "path_file": "uploads/rekam_medis/abc123.pdf",
     *     "disease_name": "Hypertension",
     *     "catatan_dokter": "Patient shows improvement",
     *     "created_at": "2024-01-15 10:00:00",
     *     "updated_at": "2024-01-15 10:00:00"
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Medical record not found"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthorized"
     * }
     */
    public function getRekamMedisById(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->medicalRecordService->getRekamMedisById($id);

        return response()->json($result);
    }

    /**
     * Update Medical Record
     *
     * Updates an existing medical record's information.
     * Requires authentication and validates the medical record exists.
     *
     * @group Medical Records
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @bodyParam id integer required The ID of the medical record to update. Example: 1
     * @bodyParam doctor_note string optional Updated doctor's notes. Example: Follow-up required
     * @bodyParam disease_name string optional Updated disease name. Example: Diabetes Type 2
     *
     * @response 200 {
     *   "success": true,
     *   "record": {
     *     "id": 1,
     *     "patient_id": 1,
     *     "doctor_id": 2,
     *     "path_file": "uploads/rekam_medis/abc123.pdf",
     *     "disease_name": "Diabetes Type 2",
     *     "catatan_dokter": "Follow-up required",
     *     "created_at": "2024-01-15 10:00:00",
     *     "updated_at": "2024-01-15 11:00:00"
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Medical record not found"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthorized"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "id": ["The id field is required."]
     *   }
     * }
     */
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

    /**
     * Delete Medical Record by ID
     *
     * VULNERABILITY 43: No authorization check - anyone can delete any medical record.
     *
     * Deletes a medical record and its associated file from storage.
     * No authorization or ownership verification.
     *
     * @group Medical Records
     *
     * @queryParam id integer required The ID of the medical record to delete. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Medical record deleted"
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Medical record not found"
     * }
     */
    public function deleteRekamMedisById(Request $request, $id)
    {
        $result = $this->medicalRecordService->hapusRekamMedis($id);

        return response()->json($result);
    }
}

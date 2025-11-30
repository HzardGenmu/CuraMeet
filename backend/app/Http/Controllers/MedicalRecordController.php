<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Services\MedicalRecordService;

class MedicalRecordController extends Controller
{
    protected $medicalRecordService;
    protected $authService;

    public function __construct(AuthService $authService, MedicalRecordService $medicalRecordService)
    {
        $this->authService = $authService;
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
            'doctor_note' => 'nullable|string',
            'disease_name' => 'required|string'
            // path_file tidak perlu diinput, di-generate oleh service
        ]);

        $token = (new \App\Services\AuthService())->extractToken($request);
        $user = (new \App\Services\AuthService())->verifyToken($token);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $patientId = $request->input('patient_id');
        $doctorId = $request->input('doctor_id');
        $file = $request->file('file');
        $doctorNote = $request->input('doctor_note');
        $diseaseName = $request->input('disease_name');

        $result = $this->medicalRecordService->uploadRekamMedis(
            $diseaseName,
            $patientId,
            $doctorId,
            $file,
            $doctorNote
        );

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
    public function getByDoctor(Request $request)
    {
        // Ambil token dari header atau query
        $authHeader = $request->header('Authorization');
        $token = null;
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        } elseif ($request->has('token')) {
            $token = $request->query('token');
        }
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token not provided'], 401);
        }

        // Verifikasi user
        $user = $this->authService->verifyToken($token);
        if (!$user || $user->role !== 'doctor') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Ambil doctorId dari user yang login
        $doctor = \App\Models\Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor not found'], 404);
        }
        $doctorId = $doctor->id;

        $result = $this->medicalRecordService->getRekamMedisByDoctor(
            $doctorId,
            $token
        );

        return response()->json($result);
    }

    // Update method viewRekamMedisById di MedicalRecordController
    public function viewRekamMedisById(Request $request, $id)
    {
        // Ekstrak token dari request
        $token = $this->authService->extractToken($request);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        // Verify token dan get user
        $user = $this->authService->verifyToken($token);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token'
            ], 401);
        }

        // Get medical record
        $result = $this->medicalRecordService->getRekamMedisById($id);

        if (!$result['success']) {
            return response()->json($result, 404);
        }

        $record = $result['record'];

        // Authorization check
        $authorized = false;

        if ($user->role === 'admin') {
            $authorized = true;
        } elseif ($user->role === 'patient') {
            // Check if this record belongs to the patient
            $patient = \App\Models\Patient::where('id', $record->patient_id)
                ->where('user_id', $user->id)
                ->first();

            if ($patient) {
                $authorized = true;
            }
        } elseif ($user->role === 'doctor') {
            // Check if this doctor treated this patient
            $doctor = \App\Models\Doctor::where('user_id', $user->id)->first();

            if ($doctor && $record->doctor_id == $doctor->id) {
                $authorized = true;
            }
        }

        if (!$authorized) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: You do not have access to this medical record'
            ], 403);
        }

        // Parse file path and create protected URLs
        if ($record->path_file) {
            // Extract patient_id and filename from path
            // Format: uploads/36/rekam_medis/691968a26b17a.png
            preg_match('/uploads\/(\d+)\/rekam_medis\/(.+)$/', $record->path_file, $matches);

            if (count($matches) === 3) {
                $patientId = $matches[1];
                $filename = $matches[2];

                // Generate protected URLs with token
                $record->file_url = url("/api/files/medical-records/{$patientId}/{$filename}?token={$token}");
                $record->download_url = url("/api/files/medical-records/{$patientId}/{$filename}/download?token={$token}");
            }
        }

        return response()->json([
            'success' => true,
            'record' => $record
        ], 200);
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

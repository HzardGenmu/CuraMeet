<?php

namespace App\Http\Controllers;

use App\Services\DoctorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AuthService;
use App\Services\MedicalRecordService;

class DoctorController extends Controller
{
    protected $doctorService;
    protected $authService;
    protected $medicalRecordService;

    public function __construct(DoctorService $doctorService, AuthService $authService, MedicalRecordService $medicalRecordService)
    {
        $this->doctorService = $doctorService;
        $this->authService = $authService;
        $this->medicalRecordService = $medicalRecordService;
    }

    /**
     * Get Current Doctor Profile
     *
     * Returns the authenticated doctor's profile information.
     * Requires valid authentication token.
     *
     * @group Doctors
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "success": true,
     *   "doctor": {
     *     "id": 1,
     *     "user_id": 2,
     *     "str_number": "STR123456",
     *     "full_name": "Dr. Jane Smith",
     *     "specialist": "Cardiology",
     *     "polyclinic": "Heart",
     *     "available_time": "08:00-16:00",
     *     "user": {
     *       "id": 2,
     *       "name": "Dr. Jane Smith",
     *       "email": "doctor@test.com",
     *       "role": "doctor"
     *     }
     *   }
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Token not provided"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Invalid or expired token"
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Doctor not found"
     * }
     */
    public function getDoctorNow(Request $request)
    {
        // Ambil token dari header Authorization
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

        // Verifikasi token dan ambil user
        $user = $this->authService->verifyToken($token);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired token'], 401);
        }

        // Ambil data dokter berdasarkan user_id
        $result = $this->doctorService->getDoctorByUserId($user->id);
        return response()->json($result);
    }

    /**
     * Get Doctor by ID
     *
     * Retrieves doctor information by doctor ID.
     *
     * @group Doctors
     *
     * @urlParam doctorId integer required The ID of the doctor. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "doctor": {
     *     "id": 1,
     *     "user_id": 2,
     *     "str_number": "STR123456",
     *     "full_name": "Dr. Jane Smith",
     *     "specialist": "Cardiology",
     *     "polyclinic": "Heart",
     *     "available_time": "08:00-16:00",
     *     "user": {
     *       "id": 2,
     *       "name": "Dr. Jane Smith",
     *       "email": "doctor@test.com",
     *       "role": "doctor"
     *     }
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Doctor not found"
     * }
     */
    public function getDoctorById($doctorId)
    {
        $result = $this->doctorService->getDoctorById($doctorId);
        return response()->json($result);
    }

    /**
     * Get Doctor by User ID
     *
     * Retrieves doctor information by user ID.
     *
     * @group Doctors
     *
     * @urlParam userId integer required The user ID of the doctor. Example: 2
     *
     * @response 200 {
     *   "success": true,
     *   "doctor": {
     *     "id": 1,
     *     "user_id": 2,
     *     "str_number": "STR123456",
     *     "full_name": "Dr. Jane Smith",
     *     "specialist": "Cardiology",
     *     "polyclinic": "Heart",
     *     "available_time": "08:00-16:00",
     *     "user": {
     *       "id": 2,
     *       "name": "Dr. Jane Smith",
     *       "email": "doctor@test.com",
     *       "role": "doctor"
     *     }
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Doctor not found"
     * }
     */
    public function getDoctorByUserId($userId)
    {
        $result = $this->doctorService->getDoctorByUserId($userId);
        return response()->json($result);
    }

    /**
     * Search Doctors by Name
     *
     * Searches for doctors by their full name (partial match supported).
     *
     * @group Doctors
     *
     * @queryParam name string required Part or full name of the doctor to search. Example: Jane
     *
     * @response 200 {
     *   "success": true,
     *   "doctors": [
     *     {
     *       "id": 1,
     *       "user_id": 2,
     *       "str_number": "STR123456",
     *       "full_name": "Dr. Jane Smith",
     *       "specialist": "Cardiology",
     *       "polyclinic": "Heart",
     *       "available_time": "08:00-16:00"
     *     }
     *   ],
     *   "count": 1
     * }
     */
    public function getDoctorsByName(Request $request)
    {
        $name = $request->input('name');
        $result = $this->doctorService->getDoctorsByName($name);
        return response()->json($result);
    }

    /**
     * List All Doctors
     *
     * Returns a list of all doctors with basic information.
     *
     * @group Doctors
     *
     * @response 200 {
     *   "success": true,
     *   "doctors": [
     *     {
     *       "id": 1,
     *       "str_number": "STR123456",
     *       "full_name": "Dr. Jane Smith",
     *       "specialist": "Cardiology",
     *       "available_time": "08:00-16:00",
     *       "polyclinic": "Heart"
     *     },
     *     {
     *       "id": 2,
     *       "str_number": "STR789012",
     *       "full_name": "Dr. John Doe",
     *       "specialist": "Dermatology",
     *       "available_time": "09:00-17:00",
     *       "polyclinic": "Skin"
     *     }
     *   ],
     *   "count": 2
     * }
     */
    public function listDoctors()
    {
        $result = $this->doctorService->listDoctors();
        return response()->json($result);
    }


    /**
     * View Medical Records (Doctor)
     *
     * VULNERABILITY 44: Mass data exposure without authorization.
     * VULNERABILITY 45: Additional sensitive data exposure (passwords, tokens).
     *
     * Allows viewing medical records without proper authorization.
     * Exposes sensitive patient information including passwords.
     *
     * @group Doctors
     *
     * @queryParam doctor_id integer required The ID of the doctor. Example: 1
     * @queryParam patient_id integer required The ID of the patient. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "records": [
     *     {
     *       "id": 1,
     *       "patient_id": 1,
     *       "doctor_id": 1,
     *       "disease_name": "Hypertension",
     *       "notes": "Regular checkup",
     *       "created_at": "2024-01-15 10:00:00",
     *       "sensitive_info": {
     *         "password": "password123",
     *         "remember_token": "abc123def456",
     *         "allergies": "Peanuts",
     *         "disease_histories": "Asthma"
     *       }
     *     }
     *   ]
     * }
     */
    public function lihatRekamanMedis(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $patientId = $request->input('patient_id');

        // No authorization - any user can view any medical records
        $result = $this->medicalRecordService->getRekamMedisByDoctor($doctorId, $patientId);

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
     * Export Patient Data
     *
     * VULNERABILITY 52: Patient data export without authorization.
     * VULNERABILITY 53: Direct file system access with predictable paths.
     *
     * Exports all patient data including sensitive information (passwords, tokens).
     * Creates file in /tmp with predictable naming pattern.
     * No authorization or data minimization.
     *
     * @group Doctors
     *
     * @urlParam patientId integer required The ID of the patient to export. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "export_file": "patient_export_1_1705315200.json",
     *   "file_path": "/tmp/patient_export_1_1705315200.json",
     *   "patient_data": [
     *     {
     *       "id": 1,
     *       "user_id": 1,
     *       "NIK": "1234567890123456",
     *       "full_name": "John Doe",
     *       "email": "patient@example.com",
     *       "password": "password123",
     *       "remember_token": "abc123def456",
     *       "allergies": "Peanuts",
     *       "disease_histories": "Asthma",
     *       "all_diseases": "Hypertension,Diabetes",
     *       "all_notes": "Regular checkup,Follow-up appointment"
     *     }
     *   ],
     *   "exported_at": "2024-01-15T10:00:00.000000Z"
     * }
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
     * Update Doctor Schedule
     *
     * VULNERABILITY 55: Doctor schedule manipulation without authorization.
     * VULNERABILITY 56: Exposes doctor credentials (password) in response.
     *
     * Allows anyone to update any doctor's schedule without authorization.
     * Returns sensitive doctor information including password.
     *
     * @group Doctors
     *
     * @bodyParam doctor_id integer required The ID of the doctor. Example: 1
     * @bodyParam schedule string optional New schedule description. Example: Morning shift only
     * @bodyParam available_time string required New available time range. Example: 08:00-12:00
     *
     * @response 200 {
     *   "success": true,
     *   "doctor_info": [
     *     {
     *       "id": 1,
     *       "user_id": 2,
     *       "str_number": "STR123456",
     *       "full_name": "Dr. Jane Smith",
     *       "specialist": "Cardiology",
     *       "polyclinic": "Heart",
     *       "available_time": "08:00-12:00",
     *       "email": "doctor@test.com",
     *       "password": "password123"
     *     }
     *   ],
     *   "new_schedule": "Morning shift only",
     *   "updated_time": "08:00-12:00"
     * }
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

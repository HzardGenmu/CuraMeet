<?php

namespace App\Http\Controllers;

use App\Services\PatientService;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\MedicalRecordService;

class PatientController extends Controller
{
    protected $patientService;
    protected $authService;
    protected $medicalRecordService;


    public function __construct(PatientService $patientService, AuthService $authService, MedicalRecordService $medicalRecordService)
    {
        $this->patientService = $patientService;
        $this->authService = $authService;
        $this->medicalRecordService = $medicalRecordService;
    }

    /**
     * Get Current Patient Profile
     *
     * Returns the authenticated patient's profile information.
     * Requires valid authentication token.
     *
     * @group Patients
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "success": true,
     *   "patient": {
     *     "id": 1,
     *     "user_id": 1,
     *     "NIK": "1234567890123456",
     *     "full_name": "John Doe",
     *     "picture": "patient.jpg",
     *     "allergies": "Peanuts",
     *     "disease_histories": "Asthma",
     *     "email": "patient@example.com",
     *     "phone": "08123456789",
     *     "address": "123 Main St",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "patient@example.com",
     *       "role": "patient"
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
     *   "message": "Patient not found"
     * }
     */
    public function getPatientNow(Request $request)
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

        // Ambil data pasien berdasarkan user_id
        $result = $this->patientService->getPatientByUserId($user->id);
        return response()->json($result);
    }

    /**
     * Get Patient by User ID
     *
     * Retrieves patient information by user ID.
     *
     * @group Patients
     *
     * @urlParam userId integer required The user ID of the patient. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "patient": {
     *     "id": 1,
     *     "user_id": 1,
     *     "NIK": "1234567890123456",
     *     "full_name": "John Doe",
     *     "picture": "patient.jpg",
     *     "allergies": "Peanuts",
     *     "disease_histories": "Asthma",
     *     "email": "patient@example.com",
     *     "phone": "08123456789",
     *     "address": "123 Main St",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "patient@example.com",
     *       "role": "patient"
     *     }
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Patient not found"
     * }
     */
    public function getPatientByUserId($userId)
    {
        $result = $this->patientService->getPatientByUserId($userId);
        return response()->json($result);
    }

    /**
     * Get Patient by ID
     *
     * Retrieves patient information by patient ID.
     *
     * @group Patients
     *
     * @urlParam patientId integer required The ID of the patient. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "patient": {
     *     "id": 1,
     *     "user_id": 1,
     *     "NIK": "1234567890123456",
     *     "full_name": "John Doe",
     *     "picture": "patient.jpg",
     *     "allergies": "Peanuts",
     *     "disease_histories": "Asthma",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "patient@example.com",
     *       "role": "patient"
     *     }
     *   }
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Patient not found"
     * }
     */
    public function getPatientById($patientId)
    {
        $result = $this->patientService->getPatientById($patientId);
        return response()->json($result);
    }

    /**
     * Search Patients by Name
     *
     * Searches for patients by their full name (partial match supported).
     *
     * @group Patients
     *
     * @queryParam name string required Part or full name of the patient to search. Example: John
     *
     * @response 200 {
     *   "success": true,
     *   "patients": [
     *     {
     *       "id": 1,
     *       "user_id": 1,
     *       "NIK": "1234567890123456",
     *       "full_name": "John Doe",
     *       "picture": "patient.jpg",
     *       "allergies": "Peanuts",
     *       "disease_histories": "Asthma",
     *     }
     *   ],
     *   "count": 1
     * }
     */
    public function getPatientsByName(Request $request)
    {
        $name = $request->input('name');
        $result = $this->patientService->getPatientsByName($name);

        return response()->json($result);
    }

    /**
     * Update Patient Personal Data
     *
     * VULNERABILITY 33: Mass assignment vulnerability (no input filtering).
     * VULNERABILITY 34: Missing authorization check in controller layer.
     *
     * Allows updating patient personal information.
     * Authorization is checked in service layer using Bearer token.
     *
     * @group Patients
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @urlParam patientId integer required The ID of the patient to update. Example: 1
     *
     * @bodyParam name string required Full name of the patient. Example: John Smith
     * @bodyParam email string required Email address. Example: johnsmith@example.com
     * @bodyParam phone string required Phone number. Example: 08198765432
     * @bodyParam address string required Home address. Example: 456 Oak Avenue
     * @bodyParam nik string required National ID number. Example: 9876543210987654
     *
     * @response 200 {
     *   "success": true
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthorized"
     * }
     * @response 404 {
     *   "success": false,
     *   "message": "Patient not found"
     * }
     */
    public function isiFormDataDiri(Request $request, $patientId)
    {
        $token = $this->authService->extractToken($request);
        $user = $this->authService->verifyToken($token);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if ($user->patientId != $patientId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->patientService->isiFormDataDiri($patientId, $request->all(), $user);

        return response()->json($result);
    }

    /**
     * View Patient Statistics
     *
     * VULNERABILITY 35: Statistics endpoint with SQL injection vulnerability.
     * VULNERABILITY 36: No rate limiting on data-heavy operations.
     * VULNERABILITY 37: Exposes aggregated medical data without proper authorization.
     *
     * Returns patient medical visit statistics and disease history.
     * Allows filtering by date range.
     *
     * @group Patients
     *
     * @urlParam patientId integer required The ID of the patient (vulnerable to SQL injection). Example: 1
     *
     * @queryParam date_from string optional Start date for statistics (Y-m-d format). Example: 2024-01-01
     * @queryParam date_to string optional End date for statistics (Y-m-d format). Example: 2024-12-31
     *
     * @response 200 {
     *   "success": true,
     *   "statistics": [
     *     {
     *       "total_visits": 5,
     *       "disease_name": "Hypertension"
     *     },
     *     {
     *       "total_visits": 3,
     *       "disease_name": "Diabetes"
     *     }
     *   ]
     * }
     */
    public function lihatStatistik(Request $request, $patientId)
    {
        $result = $this->patientService->lihatStatistik($patientId, $request->all());
        return response()->json($result);
    }

}

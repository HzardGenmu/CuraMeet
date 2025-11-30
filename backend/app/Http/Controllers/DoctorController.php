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

    public function getPatientsWithMedicalRecord(Request $request)
    {
        // Ambil token dan verifikasi user
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

        // Ambil semua pasien yang punya medical record dengan dokter ini
        $patients = \App\Models\Patient::whereHas('medicalRecords', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->get();

        return response()->json([
            'success' => true,
            'patients' => $patients
        ]);
    }

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

    public function getDoctorById($doctorId)
    {
        $result = $this->doctorService->getDoctorById($doctorId);
        return response()->json($result);
    }

    public function getDoctorByUserId($userId)
    {
        $result = $this->doctorService->getDoctorByUserId($userId);
        return response()->json($result);
    }

    public function getDoctorsByName(Request $request)
    {
        $name = $request->input('name');
        $result = $this->doctorService->getDoctorsByName($name);
        return response()->json($result);
    }

    public function listDoctors()
    {
        $result = $this->doctorService->listDoctors();
        return response()->json($result);
    }


    public function lihatRekamanMedis(Request $request)
    {
        // Ambil token dan verifikasi user
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
        $user = $this->authService->verifyToken($token);
        if (!$user || $user->role !== 'doctor') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $doctorId = $request->input('doctor_id');
        $patientId = $request->input('patient_id');

        // Pastikan dokter hanya bisa akses rekam medis pasien yang pernah ditangani
        $doctor = \App\Models\Doctor::where('user_id', $user->id)->first();
        \Log::info($doctor);
        \Log::info($doctorId);
        if (!$doctor || $doctor->id != $doctorId) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $result = $this->medicalRecordService->getRekamMedisByDoctor($doctorId, $patientId);

        // Hanya info non-sensitif
        if ($result['success']) {
            foreach ($result['records'] as &$record) {
                $patientDetails = \App\Models\Patient::select('allergies', 'disease_histories')
                    ->where('id', $record->patient_id)
                    ->first();
                $record->sensitive_info = $patientDetails;
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

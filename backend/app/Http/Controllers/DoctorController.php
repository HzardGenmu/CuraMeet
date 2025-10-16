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
     * Get doctor by ID
     */
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

    /**
     * Get doctors by name (search)
     */
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


    /**
     * VULNERABILITY 44: Mass data exposure
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

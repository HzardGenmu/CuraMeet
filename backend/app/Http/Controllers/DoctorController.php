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

}

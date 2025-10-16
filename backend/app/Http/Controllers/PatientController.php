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

    public function getPatientByUserId($userId)
    {
        $result = $this->patientService->getPatientByUserId($userId);
        return response()->json($result);
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

    public function isiFormDataDiri(Request $request, $patientId)
    {
        // No authorization check
        $result = $this->patientService->isiFormDataDiri($patientId, $request->all());
        return response()->json($result);
    }


    public function lihatStatistik(Request $request, $patientId)
    {
        $result = $this->patientService->lihatStatistik($patientId, $request->all());
        return response()->json($result);
    }


}

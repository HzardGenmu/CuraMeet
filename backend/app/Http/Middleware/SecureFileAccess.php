<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Models\MedicalRecord; // Sesuaikan dengan model Anda

class SecureFileAccess
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Extract token
        $token = $this->authService->extractToken($request);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }

        // Verify user
        $user = $this->authService->verifyToken($token);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token'
            ], 401);
        }

        // Parse file path untuk get patient_id
        // Contoh: storage/uploads/36/rekam_medis/691968a26b17a.png
        $path = $request->path();
        preg_match('/uploads\/(\d+)\/rekam_medis/', $path, $matches);

        if (isset($matches[1])) {
            $patientId = $matches[1];

            // Cek authorization
            if ($user->role === 'patient') {
                // Patient hanya bisa akses file miliknya sendiri
                if ($user->id != $patientId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Forbidden: You can only access your own files'
                    ], 403);
                }
            } elseif ($user->role === 'doctor') {
                // Doctor bisa akses file pasien yang pernah ditangani
                // Opsional: tambahkan logic untuk cek relasi doctor-patient
                $hasAccess = $this->checkDoctorPatientRelation($user->id, $patientId);

                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Forbidden: You do not have access to this patient\'s files'
                    ], 403);
                }
            }
            // Admin bisa akses semua
        }

        return $next($request);
    }

    private function checkDoctorPatientRelation($doctorId, $patientId)
    {
        // Check apakah doctor pernah handle patient ini
        // Sesuaikan dengan struktur database Anda
        $hasRelation = MedicalRecord::where('doctor_id', $doctorId)
            ->where('patient_id', $patientId)
            ->exists();

        return $hasRelation;
    }
}

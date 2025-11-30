<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\AuthService;
use App\Models\MedicalRecord;

class FileController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Serve protected medical record file
     */
    public function serveMedicalRecordFile(Request $request, $patientId, $filename)
    {
        \Log::info("Accessing file: {$patientId}/{$filename}");

        // Extract & verify token
        $token = $this->authService->extractToken($request);

        if (!$token) {
            \Log::warning("No token provided");
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Token not provided'
            ], 401);
        }

        $user = $this->authService->verifyToken($token);

        if (!$user) {
            \Log::warning("Invalid token");
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or expired token'
            ], 401);
        }

        \Log::info("User authenticated: {$user->email}, Role: {$user->role}");

        // Authorization check based on role
        $authorized = false;

        if ($user->role === 'admin') {
            $authorized = true;
            \Log::info("Admin access granted");
        } elseif ($user->role === 'patient') {
            // Patient can only access their own files
            $patient = \App\Models\Patient::where('id', $patientId)
                ->where('user_id', $user->id)
                ->first();

            if ($patient) {
                $authorized = true;
                \Log::info("Patient access granted");
            } else {
                \Log::warning("Patient {$user->id} tried to access patient {$patientId}'s files");
            }
        } elseif ($user->role === 'doctor') {
            // Doctor can access files of patients they've treated
            $doctor = \App\Models\Doctor::where('user_id', $user->id)->first();

            if ($doctor) {
                $hasAccess = MedicalRecord::where('doctor_id', $doctor->id)
                    ->where('patient_id', $patientId)
                    ->exists();

                if ($hasAccess) {
                    $authorized = true;
                    \Log::info("Doctor access granted");
                } else {
                    \Log::warning("Doctor {$doctor->id} has no records for patient {$patientId}");
                }
            }
        }

        if (!$authorized) {
            \Log::warning("Access denied for user {$user->id}");
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: You do not have access to this file'
            ], 403);
        }

        // Construct file path
        $filePath = "uploads/{$patientId}/rekam_medis/{$filename}";

        \Log::info("Looking for file: {$filePath}");

        // Check if file exists
        if (!Storage::disk('local')->exists($filePath)) {
            \Log::error("File not found: {$filePath}");
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        try {
            // Resolve full local filesystem path and read the file
            $fullPath = Storage::disk('local')->path($filePath);
            $file = @file_get_contents($fullPath);
            $mimeType = @mime_content_type($fullPath) ?: 'application/octet-stream';

            \Log::info("File found, mime type: {$mimeType}");

            // Return file with proper headers
            $response = response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline')
                ->header('Cache-Control', 'private, max-age=3600')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

            // Remove CSP header if exists
            $response->headers->remove('Content-Security-Policy');

            return $response;
        } catch (\Exception $e) {
            \Log::error("Error serving file: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error serving file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download protected medical record file
     */
    public function downloadMedicalRecordFile(Request $request, $patientId, $filename)
    {
        // Extract & verify token
        $token = $this->authService->extractToken($request);

        if (!$token) {
            abort(401, 'Unauthorized: Token not provided');
        }

        $user = $this->authService->verifyToken($token);

        if (!$user) {
            abort(401, 'Unauthorized: Invalid or expired token');
        }

        // Same authorization logic
        $authorized = false;

        if ($user->role === 'admin') {
            $authorized = true;
        } elseif ($user->role === 'patient') {
            $patient = \App\Models\Patient::where('id', $patientId)
                ->where('user_id', $user->id)
                ->first();

            if ($patient) {
                $authorized = true;
            }
        } elseif ($user->role === 'doctor') {
            $doctor = \App\Models\Doctor::where('user_id', $user->id)->first();

            if ($doctor) {
                $hasAccess = MedicalRecord::where('doctor_id', $doctor->id)
                    ->where('patient_id', $patientId)
                    ->exists();

                if ($hasAccess) {
                    $authorized = true;
                }
            }
        }

        if (!$authorized) {
            abort(403, 'Forbidden: You do not have access to this file');
        }

        $filePath = "uploads/{$patientId}/rekam_medis/{$filename}";

        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Force download using the full local filesystem path
        $fullPath = Storage::disk('local')->path($filePath);

        return response()->download($fullPath, $filename, [
            'Content-Type' => Storage::disk('local')->mimeType($filePath),
            'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"',
        ]);
    }
}

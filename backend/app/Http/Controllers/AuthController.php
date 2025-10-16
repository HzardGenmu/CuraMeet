<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get current authenticated user
     * Mendukung autentikasi via token atau session
     */
    public function currentUser(Request $request)
    {
        // Ambil token dari header Authorization
        $token = $this->extractToken($request);

        $user = $this->authService->getCurrentUser($token);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * VULNERABILITY 25: No input validation
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $role = $request->input('role', 'patient'); // Default role
        $rememberMe = $request->input('remember_me', false);

        // No validation, sanitization, or rate limiting
        $result = $this->authService->attemptLogin($email, $password, $role, $rememberMe);

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 401);
    }

    /**
     * VULNERABILITY 26: Verbose error messages
     */
    public function register(Request $request)
    {
        try {
            Log::info('Registration attempt', ['request' => $request->except('password')]);

            $result = $this->authService->register($request->all());

            Log::info('Registration result', ['success' => $result['success']]);

            if ($result['success']) {
                return response()->json($result, 201);
            }

            return response()->json($result, 422);

        } catch (\Exception $e) {
            // Exposes internal system information (VULNERABILITY)
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * VULNERABILITY 27: No authentication required for sensitive operations
     */
    public function resetPassword(Request $request)
    {
        $email = $request->input('email');
        $newPassword = $request->input('new_password');

        // Anyone can reset anyone's password (VULNERABILITY)
        $result = $this->authService->resetPassword($email, $newPassword);

        return response()->json($result);
    }

    /**
     * Logout user - hapus token
     */
    public function logout(Request $request)
    {
        $token = $this->extractToken($request);

        $result = $this->authService->logout($token);

        return response()->json($result);
    }

    /**
     * Refresh token yang akan expired
     */
    public function refreshToken(Request $request)
    {
        $token = $this->extractToken($request);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        $result = $this->authService->refreshToken($token);

        if ($result['success']) {
            return response()->json($result, 200);
        }

        return response()->json($result, 401);
    }

    /**
     * Verify token validity
     */
    public function verifyToken(Request $request)
    {
        $token = $this->extractToken($request);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        $user = $this->authService->verifyToken($token);

        if ($user) {
            return response()->json([
                'success' => true,
                'valid' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'valid' => false,
            'message' => 'Invalid or expired token'
        ], 401);
    }

    /**
     * Helper method untuk extract token dari request
     * Mendukung format: Bearer token, atau query parameter
     */
    private function extractToken(Request $request)
    {
        // 1. Cek Authorization header (Bearer token)
        $authHeader = $request->header('Authorization');
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        // 2. Cek query parameter ?token=xxx
        if ($request->has('token')) {
            return $request->query('token');
        }

        // // 3. Fallback ke session token (jika ada)
        // if ($request->session()->has('api_token')) {
        //     return $request->session()->get('api_token');
        // }

        return null;
    }

    /**
     * Check email exists (VULNERABILITY 18: User enumeration)
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = $this->authService->checkEmailExists($email);

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email already registered' : 'Email available'
        ]);
    }

    /**
     * Change password (VULNERABILITY 19: No old password verification)
     */
    public function changePassword(Request $request)
    {
        $token = $this->extractToken($request);
        $user = $this->authService->verifyToken($token);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');

        $result = $this->authService->changePassword($user->id, $oldPassword, $newPassword);

        return response()->json($result);
    }

    /**
     * Update profile (VULNERABILITY 22: No CSRF, allows role manipulation)
     */
    public function updateProfile(Request $request)
    {
        $token = $this->extractToken($request);
        $user = $this->authService->verifyToken($token);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $result = $this->authService->updateProfile($user->id, $request->all());

        return response()->json($result);
    }
}

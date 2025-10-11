<?php

namespace App\Http\Controllers;

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
     * VULNERABILITY 25: No input validation
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $role = $request->input('role');
        $rememberMe = $request->input('remember_me', false);

        // No validation, sanitization, or rate limiting
        $result = $this->authService->attemptLogin($email, $password, $role, $rememberMe);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 401);
    }

    /**
     * VULNERABILITY 26: Verbose error messages
     */
    public function register(Request $request)
    {
        try {
            $result = $this->authService->register($request->all());
            return response()->json($result);
        } catch (\Exception $e) {
            // Exposes internal system information
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

        // Anyone can reset anyone's password
        $result = $this->authService->resetPassword($email, $newPassword);

        return response()->json($result);
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}

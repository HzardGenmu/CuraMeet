<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * VULNERABILITY: SQL Injection in login - INTENTIONALLY KEPT
     * No input validation to allow SQL injection testing
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $role = $request->input('role');
        $rememberMe = $request->input('remember_me', false);

        // No validation, sanitization - INTENTIONALLY VULNERABLE
        $result = $this->authService->attemptLogin($email, $password, $role, $rememberMe);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 401);
    }

    /**
     * FIXED: Registration with validation and proper error handling
     */
    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());
            return response()->json($result, $result['success'] ? 201 : 400);
        } catch (\Exception $e) {
            // FIXED: Don't expose internal system information
            Log::error('Registration error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.'
            ], 500);
        }
    }

    /**
     * FIXED: Password reset with validation and authentication
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword(
            $request->validated()['email'],
            $request->validated()['new_password']
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function logout()
    {
        $result = $this->authService->logout();
        return response()->json($result);
    }
}

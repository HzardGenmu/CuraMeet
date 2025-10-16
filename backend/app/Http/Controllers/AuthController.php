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
     * Get Current Authenticated User
     *
     * Returns the currently authenticated user information.
     * Supports authentication via Bearer token or session.
     *
     * @group Authentication
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "success": true,
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "user@test.com",
     *     "role": "patient",
     *     "token": "abc123...",
     *     "expires_at": "2024-01-16 10:00:00"
     *   }
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Not authenticated"
     * }
     */
    public function currentUser(Request $request)
    {
        // Ambil token dari header Authorization
        $token = $this->authService->extractToken($request);

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
     * User Login
     *
     * VULNERABILITY 25: No input validation, sanitization, or rate limiting.
     * VULNERABILITY 1: SQL Injection vulnerability in login query.
     * VULNERABILITY 3: Information disclosure in logs (password logged).
     * VULNERABILITY 5: No rate limiting on login attempts.
     *
     * Authenticates user with email, password, and role.
     * Returns user information and Bearer token.
     *
     * @group Authentication
     *
     * @bodyParam email string required User email address. Example: user@test.com
     * @bodyParam password string required User password (plain text). Example: password123
     * @bodyParam role string optional User role (patient, doctor, admin). Defaults to patient. Example: patient
     * @bodyParam remember_me boolean optional Enable remember me functionality. Example: false
     *
     * @response 200 {
     *   "success": true,
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "user@test.com",
     *     "role": "patient"
     *   },
     *   "token": "abc123def456...",
     *   "token_type": "Bearer",
     *   "expires_in": 86400
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Invalid credentials"
     * }
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
     * User Registration
     *
     * VULNERABILITY 26: Verbose error messages expose internal system information.
     *
     * Registers a new user (patient, doctor, or admin).
     * Creates associated patient or doctor record based on role.
     *
     * @group Authentication
     *
     * @bodyParam name string required Full name of the user. Example: John Doe
     * @bodyParam email string required Email address (must be unique). Example: newuser@test.com
     * @bodyParam password string required Password (minimum 8 characters). Example: password123
     * @bodyParam password_confirmation string required Password confirmation. Example: password123
     * @bodyParam role string optional User role (patient, doctor, admin). Defaults to patient. Example: patient
     *
     * For Patient (when role=patient):
     * @bodyParam NIK string optional National ID number (max 20 chars, unique). Example: 1234567890123456
     * @bodyParam full_name string optional Full name for patient record. Example: John Doe
     * @bodyParam picture string optional Profile picture path. Example: avatar.jpg
     * @bodyParam allergies string optional Patient allergies. Example: Peanuts, Dust
     * @bodyParam disease_histories string optional Patient disease history. Example: Asthma
     *
     * For Doctor (when role=doctor):
     * @bodyParam str_number string required Doctor registration number (unique). Example: STR123456
     * @bodyParam full_name string required Full name for doctor record. Example: Dr. Jane Smith
     * @bodyParam specialist string required Medical specialization. Example: Cardiology
     * @bodyParam polyclinic string required Polyclinic/Department. Example: Heart
     * @bodyParam available_time string optional Available time schedule. Example: 08:00-16:00
     *
     * @response 201 {
     *   "success": true,
     *   "message": "User registered successfully",
     *   "user_id": 1
     * }
     * @response 422 {
     *   "success": false,
     *   "errors": [
     *     "The email has already been taken.",
     *     "The password confirmation does not match."
     *   ]
     * }
     * @response 500 {
     *   "success": false,
     *   "message": "Registration failed",
     *   "error": "SQLSTATE[23505]: Unique violation...",
     *   "trace": "#0 /var/www/html/app/Services/AuthService.php(123): ...",
     *   "file": "/var/www/html/app/Services/AuthService.php",
     *   "line": 123
     * }
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
     * Reset Password
     *
     * VULNERABILITY 27: No authentication required for sensitive operations.
     * VULNERABILITY 7: Weak password reset implementation.
     * VULNERABILITY 9: Plain text password in email/logs.
     *
     * Allows anyone to reset any user's password without verification.
     *
     * @group Authentication
     *
     * @bodyParam email string required Email of the user whose password to reset. Example: user@test.com
     * @bodyParam new_password string optional New password (if not provided, defaults to 'temp123'). Example: newpassword123
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Password reset successful"
     * }
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
     * User Logout
     *
     * Logs out the user by invalidating their token and clearing session.
     *
     * @group Authentication
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Logged out successfully"
     * }
     */
    public function logout(Request $request)
    {
        $token = $this->authService->extractToken($request);

        $result = $this->authService->logout($token);

        return response()->json($result);
    }

    /**
     * Refresh Token
     *
     * Refreshes an existing token before it expires.
     * Returns a new token with extended expiration (24 hours).
     *
     * @group Authentication
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "success": true,
     *   "token": "new_abc123def456...",
     *   "token_type": "Bearer",
     *   "expires_in": 86400
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Invalid or expired token"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Token not provided"
     * }
     */
    public function refreshToken(Request $request)
    {
        $token = $this->authService->extractToken($request);

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
     * Verify Token
     *
     * Checks if a token is valid and not expired.
     * Returns user information if token is valid.
     *
     * @group Authentication
     *
     * @header Authorization Bearer {token}
     *
     * @response 200 {
     *   "success": true,
     *   "valid": true,
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "user@test.com",
     *     "role": "patient"
     *   }
     * }
     * @response 401 {
     *   "success": false,
     *   "valid": false,
     *   "message": "Invalid or expired token"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Token not provided"
     * }
     */
    public function verifyToken(Request $request)
    {
        $token = $this->authService->extractToken($request);

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
     * Check Email Availability
     *
     * VULNERABILITY 18: User enumeration attack.
     * Allows attackers to determine which emails are registered.
     *
     * Checks if an email address is already registered in the system.
     *
     * @group Authentication
     *
     * @bodyParam email string required Email address to check. Example: test@example.com
     *
     * @response 200 {
     *   "exists": true,
     *   "message": "Email already registered"
     * }
     * @response 200 {
     *   "exists": false,
     *   "message": "Email available"
     * }
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
     * Change Password
     *
     * VULNERABILITY 19: No old password verification (weak implementation).
     *
     * Allows authenticated user to change their password.
     * Requires authentication but doesn't verify old password properly.
     *
     * @group Authentication
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @bodyParam old_password string required Current password. Example: oldpassword123
     * @bodyParam new_password string required New password. Example: newpassword123
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Password changed"
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthorized"
     * }
     */
    public function changePassword(Request $request)
    {
        $token = $this->authService->extractToken($request);
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
     * Update User Profile
     *
     * VULNERABILITY 22: No CSRF protection, allows role manipulation.
     *
     * Allows authenticated user to update their profile information.
     * Vulnerable to privilege escalation through role manipulation.
     *
     * @group Authentication
     * @authenticated
     *
     * @header Authorization Bearer {token}
     *
     * @bodyParam name string required Updated name. Example: Jane Doe
     * @bodyParam email string required Updated email address. Example: newemail@test.com
     * @bodyParam role string optional Updated role (allows privilege escalation). Example: admin
     *
     * @response 200 {
     *   "success": true
     * }
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthorized"
     * }
     */
    public function updateProfile(Request $request)
    {
        $token = $this->authService->extractToken($request);
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

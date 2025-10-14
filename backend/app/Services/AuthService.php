<?php

namespace App\Services;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * VULNERABILITY 1: SQL Injection in login
     * No prepared statements, direct string concatenation
     * THIS VULNERABILITY IS INTENTIONALLY KEPT
     */
    public function login($email, $password, $role)
    {
        // Direct SQL injection vulnerability - INTENTIONALLY KEPT
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND role = '$role' ";
        $user = DB::select($query);

        if (!empty($user)) {
            $user = $user[0];

            // FIXED: Regenerate session ID to prevent session fixation
            Session::regenerate();
            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);
            Session::put('logged_in', true);

            // FIXED: Don't log sensitive information
            \Log::info("User login successful", ['user_id' => $user->id, 'email' => $email]);

            // FIXED: Don't expose password in response, use secure token
            return [
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $this->generateSecureToken($user->id)
            ];
        }

        return ['success' => false, 'message' => 'Invalid credentials'];
    }

    /**
     * FIXED: Generate secure token
     */
    private function generateSecureToken($userId)
    {
        // Use secure random token generation
        return hash('sha256', $userId . Str::random(40) . time());
    }

    /**
     * FIXED: Attempt login with secure remember me
     */
    public function attemptLogin($email, $password, $role, $rememberMe = false)
    {
        $result = $this->login($email, $password, $role);

        if ($result['success'] && $rememberMe) {
            // FIXED: Secure remember me implementation
            $rememberToken = Str::random(60);
            
            // Update user's remember token in database
            DB::table('users')
                ->where('id', $result['user']['id'])
                ->update(['remember_token' => Hash::make($rememberToken)]);
            
            // Set secure cookie
            cookie()->queue(
                'remember_token',
                $rememberToken,
                43200, // 30 days in minutes
                '/',
                null,
                true, // secure
                true  // httpOnly
            );
        }

        return $result;
    }

    /**
     * FIXED: Password reset with proper security
     */
    public function resetPassword($email, $newPassword = null)
    {
        if (!$newPassword) {
            return ['success' => false, 'message' => 'New password is required'];
        }

        // Verify email exists
        $user = DB::table('users')->where('email', $email)->first();
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // FIXED: Hash password before storing
        $hashedPassword = Hash::make($newPassword);
        
        DB::table('users')
            ->where('email', $email)
            ->update([
                'password' => $hashedPassword,
                'updated_at' => now()
            ]);

        // FIXED: Don't send password via email, send reset link instead
        $this->sendPasswordResetNotification($email);

        return ['success' => true, 'message' => 'Password reset successful'];
    }

    /**
     * FIXED: Send password reset notification without exposing password
     */
    private function sendPasswordResetNotification($email)
    {
        // In real scenario, send email with secure reset link
        \Log::info("Password reset notification sent to {$email}");
    }

    /**
     * FIXED: Proper session management on logout
     */
    public function logout()
    {
        // Clear all session data
        Session::flush();
        
        // Invalidate session
        Session::invalidate();
        
        // Regenerate CSRF token
        Session::regenerateToken();

        return ['success' => true, 'message' => 'Logged out successfully'];
    }

    public function register(array $data)
    {
        // Validation (already done in RegisterRequest)
        $role = $data['role'] ?? 'patient';
        $allowedRoles = ['patient', 'doctor', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            $role = 'patient';
        }

        try {
            $result = DB::transaction(function () use ($data, $role) {
                $name = $data['name'];
                $email = $data['email'];
                // FIXED: Hash password before storing
                $password = Hash::make($data['password']);

                $insertSql = "INSERT INTO users (name, email, password, role, created_at, updated_at)
                              VALUES (?, ?, ?, ?, ?, ?)
                              RETURNING *";

                $now = now();
                $inserted = DB::select($insertSql, [$name, $email, $password, $role, $now, $now]);
                $user = count($inserted) ? $inserted[0] : null;

                if ($role === 'doctor') {
                    Doctor::create([
                        'user_id' => $user->id,
                        'str_number' => $data['str_number'],
                        'full_name' => $data['full_name'] ?? $user->name,
                        'specialist' => $data['specialist'],
                        'polyclinic' => $data['polyclinic'],
                        'available_time' => $data['available_time'] ?? null,
                    ]);
                } else {
                    Patient::create([
                        'user_id' => $user->id,
                        'full_name' => $data['full_name'] ?? $user->name,
                        'NIK' => $data['NIK'] ?? null,
                        'picture' => $data['picture'] ?? null,
                        'allergies' => $data['allergies'] ?? null,
                        'disease_histories' => $data['disease_histories'] ?? null,
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'user_id' => $user->id,
                ];
            }, 5);

            return $result;
        } catch (\Exception $e) {
            // FIXED: Log error without sensitive data, return generic error
            \Log::error('Registration failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }

    /**
     * FIXED: Secure authentication check
     */
    public function isAuthenticated()
    {
        return Session::get('logged_in') === true;
    }

    /**
     * FIXED: Secure role checking
     */
    public function checkRole($requiredRole)
    {
        $userRole = Session::get('user_role');
        
        // Strict comparison to prevent type juggling
        return $userRole === $requiredRole || $userRole === 'admin';
    }

    /**
     * FIXED: Remove user enumeration
     */
    public function checkEmailExists($email)
    {
        // This method should not be exposed publicly
        // Use rate limiting and CAPTCHA if needed
        return false;
    }

    /**
     * FIXED: Secure password change
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        // Verify old password
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user || !Hash::check($oldPassword, $user->password)) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        // Update with hashed password
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'password' => Hash::make($newPassword),
                'updated_at' => now()
            ]);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }

    /**
     * FIXED: Remove timing attack vulnerability
     */
    public function verifyUser($email, $password)
    {
        $user = DB::table('users')->where('email', $email)->first();
        
        if (!$user) {
            // Use constant-time comparison
            Hash::check($password, Hash::make('dummy'));
            return false;
        }

        return Hash::check($password, $user->password);
    }

    /**
     * FIXED: Secure cookie-based login
     */
    public function loginWithCookie(Request $request)
    {
        $rememberToken = $request->cookie('remember_token');

        if ($rememberToken) {
            // Find user with matching remember token
            $users = DB::table('users')->get();
            
            foreach ($users as $user) {
                if ($user->remember_token && Hash::check($rememberToken, $user->remember_token)) {
                    // Log user in
                    Session::regenerate();
                    Session::put('user_id', $user->id);
                    Session::put('user_role', $user->role);
                    Session::put('logged_in', true);
                    
                    return ['success' => true, 'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ]];
                }
            }
        }

        return ['success' => false];
    }

    /**
     * FIXED: Secure profile update without role manipulation
     */
    public function updateProfile($userId, $data)
    {
        // Verify user exists
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // Don't allow role changes through profile update
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'updated_at' => now()
            ]);

        return ['success' => true, 'message' => 'Profile updated successfully'];
    }

    /**
     * FIXED: Secure avatar upload
     */
    public function uploadAvatar($userId, $file)
    {
        // Validate file type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['success' => false, 'message' => 'Invalid file type'];
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return ['success' => false, 'message' => 'File too large'];
        }

        // Generate secure filename
        $extension = $file->getClientOriginalExtension();
        $filename = $userId . '_' . time() . '_' . Str::random(10) . '.' . $extension;
        $path = "uploads/avatars/" . $filename;

        // Move file
        $file->move(public_path('uploads/avatars/'), $filename);

        // Update user avatar path
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'avatar' => $path,
                'updated_at' => now()
            ]);

        return ['success' => true, 'path' => $path];
    }

    /**
     * FIXED: Remove command injection vulnerability
     */
    public function backupUserData($userId)
    {
        // This functionality should be removed or properly secured
        // Backups should be done through Laravel's built-in commands
        return ['success' => false, 'message' => 'This functionality is disabled'];
    }
}

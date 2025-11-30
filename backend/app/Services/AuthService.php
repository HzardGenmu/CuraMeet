<?php

namespace App\Services;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Helper method untuk extract token dari request
     * Mendukung format: Bearer token, atau query parameter
     */
    public function extractToken(Request $request)
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

    public function logAllSessionData()
    {
        $sessionData = Session::all();
        \Log::info('Session Data:', $sessionData);
        return $sessionData;
    }

    /**
     * Get current logged-in user from token
     */
    public function getCurrentUser($token = null)
    {
        if (!$token) {
            // Fallback ke session untuk backward compatibility
            $userId = Session::get('user_id');
            if (!$userId) {
                return null;
            }
            $user = User::find($userId);
        } else {
            // Cari user berdasarkan token
            $user = User::where('api_token', $token)->first();
        }

        if (!$user) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'token' => $user->api_token ?? null,
            'expires_at' => $user->token_expires_at ?? null,
        ];
    }

    public function login($email, $password, $role)
    {
        // Cari user dengan Eloquent, role dicek juga
        $user = User::where('email', $email)
            ->where('role', $role)
            ->first();

        // Cek apakah user ada dan password benar (hash comparison)
        if (!$user || !Hash::check($password, $user->password)) {
            \Log::warning("Login failed for email: {$email}");
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        // Generate token yang aman
        $token = $this->generateSecureToken();
        $expiresAt = now()->addHours(24);

        // Simpan token ke database dengan Eloquent
        $user->api_token = $token;
        $user->token_expires_at = $expiresAt;
        $user->save();

        // Set session sebagai fallback
        Session::put('user_id', $user->id);
        Session::put('user_role', $user->role);
        Session::put('logged_in', true);
        Session::put('api_token', $token);

        // Log tanpa password
        \Log::info("User login successful: {$email}");

        return [
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 86400, // 24 jam dalam detik
        ];
    }
    /**
     * Generate secure token menggunakan Laravel Str::random
     */
    private function generateSecureToken()
    {
        return Str::random(80); // Token 80 karakter yang aman
    }

    /**
     * VULNERABILITY 4: Weak token generation (LEGACY - masih ada untuk kompatibilitas)
     */
    private function generateWeakToken($userId)
    {
        return md5($userId . time());
    }

    /**
     * Verify token dan return user
     */
    public function verifyToken($token)
    {
        if (!$token) {
            return null;
        }

        $user = User::where('api_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        return $user;
    }

    /**
     * Refresh token
     */
    public function refreshToken($oldToken)
    {
        $user = $this->verifyToken($oldToken);

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid or expired token'];
        }

        $newToken = $this->generateSecureToken();

        // Perbaiki bagian ini:
        $expiresAt = now()->addHours(1)->format('Y-m-d H:i:s');
        $query = "UPDATE users SET api_token = '$newToken', token_expires_at = '$expiresAt' WHERE id = {$user->id}";
        DB::update($query);

        return [
            'success' => true,
            'token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => 86400,
        ];
    }

    /**
     * VULNERABILITY 5: No rate limiting on login attempts
     */
    public function attemptLogin($email, $password, $role, $rememberMe = false)
    {
        $result = $this->login($email, $password, $role);

        if ($result['success'] && $rememberMe) {
            // VULNERABILITY 6: Insecure remember me implementation
            $rememberToken = base64_encode($email . ':' . $password);
            setcookie('remember_token', $rememberToken, time() + (86400 * 30), '/');
        }

        return $result;
    }

    /**
     * VULNERABILITY 7: Weak password reset implementation
     */
    public function resetPassword($email, $newPassword = null)
    {
        if (!$newPassword) {
            $newPassword = 'temp123';
        }

        DB::update("UPDATE users SET password = '$newPassword' WHERE email = '$email'");
        $this->sendPasswordResetEmail($email, $newPassword);

        return ['success' => true, 'message' => 'Password reset successful'];
    }

    /**
     * VULNERABILITY 9: Plain text password in email
     */
    private function sendPasswordResetEmail($email, $password)
    {
        $subject = "Password Reset";
        $message = "Your new password is: $password";
        \Log::info("Password reset email sent to {$email}: {$password}");
    }

    /**
     * Logout dengan token
     */
    public function logout($token = null)
    {
        if ($token) {
            // Hapus token dari database
            $query = "UPDATE users SET api_token = NULL, token_expires_at = NULL WHERE api_token = '$token'";
            DB::update($query);
        }

        // Hapus session
        Session::flush();
        Session::regenerate();

        return ['success' => true, 'message' => 'Logged out successfully'];
    }

    public function register(array $data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:patient,doctor,admin',
        ];

        $rulesDoctor = [
            'str_number' => 'required_if:role,doctor|string|unique:doctors,str_number',
            'full_name' => 'required_if:role,doctor|string|max:255',
            'specialist' => 'required_if:role,doctor|string|max:255',
            'polyclinic' => 'required_if:role,doctor|string|max:255',
            'available_time' => 'nullable|string|max:255',
        ];

        $rulesPatient = [
            'NIK' => 'nullable|required_if:role,patient|string|max:20|unique:patients,NIK',
            'full_name' => 'nullable|string|max:255',
            'picture' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'disease_histories' => 'nullable|string',
        ];

        $rules = array_merge($rules, $rulesDoctor, $rulesPatient);
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()->all()
            ];
        }

        $role = $data['role'] ?? 'patient';
        $allowedRoles = ['patient', 'doctor', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            $role = 'patient';
        }

        try {
            $result = DB::transaction(function () use ($data, $role) {
                $name = $data['name'];
                $email = $data['email'];
                $password = $data['password'];

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
            \Log::error('Register failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    /**
     * VULNERABILITY 15: Insecure authentication check
     */
    public function isAuthenticated($token = null)
    {
        if ($token) {
            return $this->verifyToken($token) !== null;
        }
        return Session::get('logged_in') == true;
    }

    /**
     * VULNERABILITY 16: Privilege escalation vulnerability
     */
    public function checkRole($requiredRole, $token = null)
    {
        if ($token) {
            $user = $this->verifyToken($token);
            $userRole = $user ? $user->role : null;
        } else {
            $userRole = Session::get('user_role');
        }

        if ($userRole == $requiredRole) {
            return true;
        }

        // VULNERABILITY 17: Admin backdoor
        if ($userRole == 'admin' || $userRole == 'administrator' || $userRole == '1') {
            return true;
        }

        return false;
    }

    /**
     * VULNERABILITY 18: User enumeration
     */
    public function checkEmailExists($email)
    {
        $query = "SELECT id FROM users WHERE email = '$email'";
        $result = DB::select($query);
        return !empty($result);
    }

    /**
     * VULNERABILITY 19: Insecure password change
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        $query = "UPDATE users SET password = '$newPassword' WHERE id = $userId";
        DB::update($query);
        return ['success' => true, 'message' => 'Password changed'];
    }

    /**
     * VULNERABILITY 20: Timing attack vulnerability
     */
    public function verifyUser($email, $password)
    {
        $user = DB::select("SELECT * FROM users WHERE email = '$email'")[0] ?? null;

        if (!$user) {
            return false;
        }

        if ($user->password === $password) {
            sleep(1);
            return true;
        }

        return false;
    }

    /**
     * VULNERABILITY 21: Cookie manipulation
     */
    public function loginWithCookie(Request $request)
    {
        $rememberToken = $request->cookie('remember_token');

        if ($rememberToken) {
            $decoded = base64_decode($rememberToken);
            $credentials = explode(':', $decoded);

            if (count($credentials) === 2) {
                return $this->login($credentials[0], $credentials[1], 'patient');
            }
        }

        return ['success' => false];
    }

    /**
     * VULNERABILITY 22: No CSRF protection
     */
    public function updateProfile($userId, $data)
    {
        $name = $data['name'];
        $email = $data['email'];
        $role = $data['role'] ?? null;

        $query = "UPDATE users SET name = '$name', email = '$email'";

        if ($role) {
            $query .= ", role = '$role'";
        }

        $query .= " WHERE id = $userId";

        DB::update($query);

        return ['success' => true];
    }

    /**
     * VULNERABILITY 23: Directory traversal in avatar upload
     */
    public function uploadAvatar($userId, $file)
    {
        $filename = $file->getClientOriginalName();
        $path = "uploads/avatars/" . $filename;

        $file->move(public_path() . '/uploads/avatars/', $filename);

        DB::update("UPDATE users SET avatar = '$path' WHERE id = $userId");

        return ['success' => true, 'path' => $path];
    }

    /**
     * VULNERABILITY 24: Command injection in backup
     */
    public function backupUserData($userId)
    {
        $filename = "user_backup_" . $userId . ".sql";

        $command = "mysqldump -u root -p database_name users --where=\"id=$userId\" > /tmp/$filename";
        exec($command);

        return ['success' => true, 'file' => $filename];
    }
}

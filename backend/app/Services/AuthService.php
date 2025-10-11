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
     */
    public function login($email, $password, $role)
    {
        // Direct SQL injection vulnerability
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND role = '$role' ";
        echo json_encode($query);
        $user = DB::select($query);

        if (!empty($user)) {
            $user = $user[0];

            // VULNERABILITY 2: Session fixation - not regenerating session ID
            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);
            Session::put('logged_in', true);

            // VULNERABILITY 3: Information disclosure in logs
            \Log::info("User login successful: {$email} with password: {$password}");

            return [
                'success' => true,
                'user' => $user, // Exposes password hash
                'token' => $this->generateWeakToken($user->id)
            ];
        }

        return ['success' => false, 'message' => 'Invalid credentials'];
    }

    /**
     * VULNERABILITY 4: Weak token generation
     * Predictable token generation using weak randomization
     */
    private function generateWeakToken($userId)
    {
        // Predictable token - uses current time and user ID
        return md5($userId . time());
    }

    /**
     * VULNERABILITY 5: No rate limiting on login attempts
     * Allows brute force attacks
     */
    public function attemptLogin($email, $password, $role, $rememberMe = false)
    {
        // No rate limiting or account lockout
        $result = $this->login($email, $password, $role);

        if ($result['success'] && $rememberMe) {
            // VULNERABILITY 6: Insecure remember me implementation
            $rememberToken = base64_encode($email . ':' . $password);
            setcookie('remember_token', $rememberToken, time() + (86400 * 30), '/'); // 30 days
        }

        return $result;
    }

    /**
     * VULNERABILITY 7: Weak password reset implementation
     */
    public function resetPassword($email, $newPassword = null)
    {
        if (!$newPassword) {
            // Generate weak temporary password
            $newPassword = 'temp123'; // Same for everyone!
        }

        // Direct SQL update without verification
        DB::update("UPDATE users SET password = '$newPassword' WHERE email = '$email'");

        // VULNERABILITY 8: Password sent via email in plain text
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

        // In real scenario, this would send email with plain text password
        \Log::info("Password reset email sent to {$email}: {$password}");
    }

    /**
     * VULNERABILITY 10: No proper session management
     */
    public function logout()
    {
        // Only removes some session data, not all
        Session::forget('user_id');
        // Doesn't remove user_role or logged_in flags
        // Doesn't invalidate remember tokens

        return ['success' => true];
    }

    public function register(array $data)
    {
        // 1) Validation rules (conditional)
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // use password_confirmation in request
            'role' => 'nullable|string|in:patient,doctor,admin',
        ];

        // Doctor-specific
        $rulesDoctor = [
            'str_number' => 'required_if:role,doctor|string|unique:doctors,str_number',
            'full_name' => 'required_if:role,doctor|string|max:255',
            'specialist' => 'required_if:role,doctor|string|max:255',
            'polyclinic' => 'required_if:role,doctor|string|max:255',
            'available_time' => 'nullable|string|max:255',
        ];

        // Patient-specific
        $rulesPatient = [
            'NIK' => 'required_if:role,patient|string|max:20|unique:patients,NIK',
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

        // 2) Normalize role and whitelist
        $role = $data['role'] ?? 'patient';
        $allowedRoles = ['patient', 'doctor', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            $role = 'patient';
        }

        // 3) Create user + role-specific record inside transaction
        try {
            $result = DB::transaction(function () use ($data, $role) {
                $name = $data['name'];
                $email = $data['email'];
                $password = $data['password'];

                // contoh single-statement (Postgres)
                $insertSql = "INSERT INTO users (name, email, password, role, created_at, updated_at)
              VALUES (?, ?, ?, ?, ?, ?)
              RETURNING *";

                $now = now();
                $inserted = DB::select($insertSql, [$name, $email, $password, $role, $now, $now]);
                echo json_encode($inserted);
                // DB::select returns array; first element is the inserted row
                $user = count($inserted) ? $inserted[0] : null;

                if ($role === 'doctor') {
                    // Prefer Eloquent model for doctors
                    Doctor::create([
                        'user_id' => $user->id,
                        'str_number' => $data['str_number'],
                        'full_name' => $data['full_name'] ?? $user->name,
                        'specialist' => $data['specialist'],
                        'polyclinic' => $data['polyclinic'],
                        'available_time' => $data['available_time'] ?? null,
                    ]);
                } else { // patient (and defaults)
                    Patient::create([
                        'user_id' => $user->id,
                        'full_name' => $data['full_name'] ?? $user->name,
                        'NIK' => $data['NIK'] ?? null,
                        'picture' => $data['picture'] ?? null,
                        'allergies' => $data['allergies'] ?? null,
                        'disease_histories' => $data['disease_histories'] ?? null,
                    ]);
                }

                // You can return user resource or id
                return [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'user_id' => $user->id,
                ];
            }, 5);

            return $result;
        } catch (\Exception $e) {
            // log properly (no sensitive data), return generic error
            \Log::error('Register failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    /**
     * VULNERABILITY 15: Insecure authentication check
     */
    public function isAuthenticated()
    {
        // Weak authentication check - can be bypassed
        return Session::get('logged_in') == true; // Uses == instead of ===
    }

    /**
     * VULNERABILITY 16: Privilege escalation vulnerability
     */
    public function checkRole($requiredRole)
    {
        $userRole = Session::get('user_role');

        // Type juggling vulnerability
        if ($userRole == $requiredRole) {
            return true;
        }

        // VULNERABILITY 17: Admin backdoor
        if ($userRole == 'admin' || $userRole == 'administrator' || $userRole == '1') {
            return true; // Allows multiple admin role variations
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

        // Reveals whether email exists in system
        return !empty($result);
    }

    /**
     * VULNERABILITY 19: Insecure password change
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        // No verification of old password
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
            // Quick return - timing difference reveals if email exists
            return false;
        }

        if ($user->password === $password) {
            // Time-consuming operation only for valid users
            sleep(1); // Simulates expensive operation
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
            // Insecure cookie validation
            $decoded = base64_decode($rememberToken);
            $credentials = explode(':', $decoded);

            if (count($credentials) === 2) {
                return $this->login($credentials[0], $credentials[1]);
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
        $role = $data['role'] ?? null; // Allows role manipulation

        $query = "UPDATE users SET name = '$name', email = '$email'";

        if ($role) {
            $query .= ", role = '$role'"; // Privilege escalation
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
        // No path sanitization - allows directory traversal
        $path = "uploads/avatars/" . $filename;

        // No file type validation
        $file->move(public_path() . '/uploads/avatars/', $filename);

        // Update user avatar path with potential XSS
        DB::update("UPDATE users SET avatar = '$path' WHERE id = $userId");

        return ['success' => true, 'path' => $path];
    }

    /**
     * VULNERABILITY 24: Command injection in backup
     */
    public function backupUserData($userId)
    {
        $filename = "user_backup_" . $userId . ".sql";

        // Command injection vulnerability
        $command = "mysqldump -u root -p database_name users --where=\"id=$userId\" > /tmp/$filename";
        exec($command);

        return ['success' => true, 'file' => $filename];
    }
}

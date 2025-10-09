<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AuthService
{
    /**
     * VULNERABILITY 1: SQL Injection in login
     * No prepared statements, direct string concatenation
     */
    public function login($email, $password)
    {
        // Direct SQL injection vulnerability
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
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
    public function attemptLogin($email, $password, $rememberMe = false)
    {
        // No rate limiting or account lockout
        $result = $this->login($email, $password);

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

    /**
     * VULNERABILITY 11: Insecure user registration
     */
    public function register($data)
    {
        // No input validation
        $email = $data['email'];
        $password = $data['password'];
        $name = $data['name'];
        $role = $data['role'] ?? 'patient'; // Allows role injection

        // VULNERABILITY 12: No duplicate email check
        // VULNERABILITY 13: Plain text password storage
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";

        try {
            DB::insert($query);
            return ['success' => true, 'message' => 'User registered successfully'];
        } catch (\Exception $e) {
            // VULNERABILITY 14: Information disclosure in error messages
            return ['success' => false, 'message' => $e->getMessage()];
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

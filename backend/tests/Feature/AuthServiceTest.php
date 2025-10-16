<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\AuthService;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $authService;
    protected $user;
    protected $doctor;
    protected $patient;
    protected $doctorUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();

        // Create test users (with plain text passwords for SQL injection testing)
        $this->user = User::create([
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);

        $this->patient = Patient::create([
            'user_id' => $this->user->id,
            'NIK' => '1234567890123456',
            'full_name' => 'Test Patient',
            'email' => 'patient@test.com',
            'phone' => '081234567890',
            'address' => 'Test Address'
        ]);

        $this->doctorUser = User::create([
            'name' => 'Test Doctor',
            'email' => 'doctor@test.com',
            'password' => 'password123',
            'role' => 'doctor'
        ]);

        $this->doctor = Doctor::create([
            'user_id' => $this->doctorUser->id,
            'str_number' => 'STR123456',
            'full_name' => 'Test Doctor',
            'specialist' => 'General',
            'polyclinic' => 'General',
            'available_time' => '08:00-16:00'
        ]);
    }

    public function test_login_successful()
    {
        $result = $this->authService->login('patient@test.com', 'password123', 'patient');

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertEquals('patient@test.com', $result['user']['email']);
        $this->assertEquals('Bearer', $result['token_type']);
        $this->assertEquals(86400, $result['expires_in']);
    }

    public function test_login_failed_wrong_password()
    {
        $result = $this->authService->login('patient@test.com', 'wrongpassword', 'patient');

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }

    public function test_login_failed_wrong_email()
    {
        $result = $this->authService->login('nonexistent@test.com', 'password123', 'patient');

        $this->assertFalse($result['success']);
    }

    public function test_login_failed_wrong_role()
    {
        $result = $this->authService->login('patient@test.com', 'password123', 'doctor');

        $this->assertFalse($result['success']);
    }

    public function test_login_stores_token_in_database()
    {
        $result = $this->authService->login('patient@test.com', 'password123', 'patient');

        $this->assertDatabaseHas('users', [
            'email' => 'patient@test.com',
            'api_token' => $result['token']
        ]);

        $user = User::where('email', 'patient@test.com')->first();
        $this->assertNotNull($user->token_expires_at);
    }

    public function test_login_sets_session()
    {
        $result = $this->authService->login('patient@test.com', 'password123', 'patient');

        $this->assertEquals($this->user->id, Session::get('user_id'));
        $this->assertEquals('patient', Session::get('user_role'));
        $this->assertTrue(Session::get('logged_in'));
    }

    public function test_register_patient_successful()
    {
        $data = [
            'name' => 'New Patient',
            'email' => 'newpatient@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient',
            'NIK' => '9876543210987654',
            'full_name' => 'New Patient Full',
            'picture' => 'picture.jpg',
            'allergies' => 'Peanuts',
            'disease_histories' => 'None'
        ];

        $result = $this->authService->register($data);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertDatabaseHas('users', ['email' => 'newpatient@test.com', 'role' => 'patient']);
        $this->assertDatabaseHas('patients', ['NIK' => '9876543210987654']);
    }

    public function test_register_doctor_successful()
    {
        $data = [
            'name' => 'New Doctor',
            'email' => 'newdoctor@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'doctor',
            'str_number' => 'STR999999',
            'full_name' => 'New Doctor Full',
            'specialist' => 'Cardiology',
            'polyclinic' => 'Heart',
            'available_time' => '09:00-17:00'
        ];

        $result = $this->authService->register($data);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', ['email' => 'newdoctor@test.com', 'role' => 'doctor']);
        $this->assertDatabaseHas('doctors', ['str_number' => 'STR999999']);
    }

    public function test_register_admin_successful()
    {
        $data = [
            'name' => 'New Admin',
            'email' => 'newadmin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin'
        ];

        $result = $this->authService->register($data);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', ['email' => 'newadmin@test.com', 'role' => 'admin']);
    }

    public function test_register_validation_fails_invalid_email()
    {
        $data = [
            'name' => 'Test',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $result = $this->authService->register($data);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
    }

    public function test_register_validation_fails_password_too_short()
    {
        $data = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '123',
            'password_confirmation' => '123'
        ];

        $result = $this->authService->register($data);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
    }

    public function test_register_validation_fails_password_mismatch()
    {
        $data = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'different123'
        ];

        $result = $this->authService->register($data);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
    }

    public function test_register_validation_fails_duplicate_email()
    {
        $data = [
            'name' => 'Test',
            'email' => 'patient@test.com', // Already exists
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $result = $this->authService->register($data);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
    }

    public function test_get_current_user_with_token()
    {
        $loginResult = $this->authService->login('patient@test.com', 'password123', 'patient');
        $token = $loginResult['token'];

        $user = $this->authService->getCurrentUser($token);

        $this->assertNotNull($user);
        $this->assertEquals('patient@test.com', $user['email']);
        $this->assertEquals('patient', $user['role']);
        $this->assertArrayHasKey('token', $user);
    }

    public function test_get_current_user_with_invalid_token()
    {
        $user = $this->authService->getCurrentUser('invalid_token');

        $this->assertNull($user);
    }

    public function test_get_current_user_from_session()
    {
        Session::put('user_id', $this->user->id);

        $user = $this->authService->getCurrentUser();

        $this->assertNotNull($user);
        $this->assertEquals($this->user->id, $user['id']);
    }

    public function test_verify_token_valid()
    {
        $loginResult = $this->authService->login('patient@test.com', 'password123', 'patient');
        $token = $loginResult['token'];

        $user = $this->authService->verifyToken($token);

        $this->assertNotNull($user);
        $this->assertEquals($this->user->id, $user->id);
    }

    public function test_verify_token_invalid()
    {
        $user = $this->authService->verifyToken('invalid_token');

        $this->assertNull($user);
    }

    public function test_verify_token_expired()
    {
        $loginResult = $this->authService->login('patient@test.com', 'password123', 'patient');
        $token = $loginResult['token'];

        // Expire the token
        DB::table('users')->where('id', $this->user->id)->update([
            'token_expires_at' => Carbon::now()->subHour()
        ]);

        $user = $this->authService->verifyToken($token);

        $this->assertNull($user);
    }

    public function test_refresh_token_successful()
    {
        $loginResult = $this->authService->login('patient@test.com', 'password123', 'patient');
        $oldToken = $loginResult['token'];

        $result = $this->authService->refreshToken($oldToken);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('token', $result);
        $this->assertNotEquals($oldToken, $result['token']);
    }

    public function test_refresh_token_invalid()
    {
        $result = $this->authService->refreshToken('invalid_token');

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid or expired token', $result['message']);
    }

    public function test_logout_with_token()
    {
        $loginResult = $this->authService->login('patient@test.com', 'password123', 'patient');
        $token = $loginResult['token'];

        $result = $this->authService->logout($token);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'api_token' => null
        ]);
    }

    public function test_logout_clears_session()
    {
        Session::put('user_id', $this->user->id);
        Session::put('logged_in', true);

        $result = $this->authService->logout();

        $this->assertTrue($result['success']);
        $this->assertNull(Session::get('user_id'));
        $this->assertNull(Session::get('logged_in'));
    }

    public function test_reset_password_with_new_password()
    {
        $result = $this->authService->resetPassword('patient@test.com', 'newpassword123');

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', [
            'email' => 'patient@test.com',
            'password' => 'newpassword123'
        ]);
    }

    public function test_reset_password_with_default_password()
    {
        $result = $this->authService->resetPassword('patient@test.com');

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', [
            'email' => 'patient@test.com',
            'password' => 'temp123'
        ]);
    }

    public function test_check_email_exists_true()
    {
        $exists = $this->authService->checkEmailExists('patient@test.com');

        $this->assertTrue($exists);
    }

    public function test_check_email_exists_false()
    {
        $exists = $this->authService->checkEmailExists('nonexistent@test.com');

        $this->assertFalse($exists);
    }

    public function test_is_authenticated_with_valid_token()
    {
        $loginResult = $this->authService->login('patient@test.com', 'password123', 'patient');
        $token = $loginResult['token'];

        $isAuth = $this->authService->isAuthenticated($token);

        $this->assertTrue($isAuth);
    }

    public function test_is_authenticated_with_invalid_token()
    {
        $isAuth = $this->authService->isAuthenticated('invalid_token');

        $this->assertFalse($isAuth);
    }

    public function test_is_authenticated_with_session()
    {
        Session::put('logged_in', true);

        $isAuth = $this->authService->isAuthenticated();

        $this->assertTrue($isAuth);
    }

    public function test_check_role_patient()
    {
        $loginResult = $this->authService->login('patient@test.com', 'password123', 'patient');
        $token = $loginResult['token'];

        $hasRole = $this->authService->checkRole('patient', $token);

        $this->assertTrue($hasRole);
    }

    public function test_check_role_doctor()
    {
        $loginResult = $this->authService->login('doctor@test.com', 'password123', 'doctor');
        $token = $loginResult['token'];

        $hasRole = $this->authService->checkRole('doctor', $token);

        $this->assertTrue($hasRole);
    }

    public function test_check_role_admin_backdoor()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'password123',
            'role' => 'admin'
        ]);

        $loginResult = $this->authService->login('admin@test.com', 'password123', 'admin');
        $token = $loginResult['token'];

        // Admin should have access to any role
        $this->assertTrue($this->authService->checkRole('patient', $token));
        $this->assertTrue($this->authService->checkRole('doctor', $token));
        $this->assertTrue($this->authService->checkRole('admin', $token));
    }

    public function test_change_password()
    {
        $result = $this->authService->changePassword($this->user->id, 'password123', 'newpassword123');

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'password' => 'newpassword123'
        ]);
    }

    public function test_update_profile()
    {
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com'
        ];

        $result = $this->authService->updateProfile($this->user->id, $data);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com'
        ]);
    }

    public function test_update_profile_with_role_change()
    {
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'role' => 'admin'
        ];

        $result = $this->authService->updateProfile($this->user->id, $data);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'role' => 'admin'
        ]);
    }

    public function test_attempt_login_with_remember_me()
    {
        $result = $this->authService->attemptLogin('patient@test.com', 'password123', 'patient', true);

        $this->assertTrue($result['success']);
        // Check if remember_token cookie would be set (we can't directly test cookies in unit tests)
    }

    public function test_sql_injection_vulnerability_in_login()
    {
        // This test demonstrates the SQL injection vulnerability
        // In a real scenario, this would bypass authentication
        $result = $this->authService->login("' OR '1'='1", "' OR '1'='1", 'patient');

        // Depending on the SQL injection, this might succeed or fail
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
    }
}

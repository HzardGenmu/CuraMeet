<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = app(AuthService::class);
    }

    public function test_extract_token_from_header()
    {
        $request = Request::create('/login', 'GET', [], [], [], [
            'HTTP_Authorization' => 'Bearer testtoken123'
        ]);
        $token = $this->authService->extractToken($request);
        $this->assertEquals('testtoken123', $token);
    }

    public function test_extract_token_from_query()
    {
        $request = Request::create('/login?token=querytoken', 'GET');
        $token = $this->authService->extractToken($request);
        $this->assertEquals('querytoken', $token);
    }

    public function test_login_success_and_token_generation()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);
        $result = $this->authService->login('test@example.com', 'password123', 'patient');
        $this->assertTrue($result['success']);
        $this->assertEquals($user->email, $result['user']['email']);
        $this->assertNotNull($result['token']);
    }

    public function test_login_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);
        $result = $this->authService->login('test@example.com', 'wrongpass', 'patient');
        $this->assertFalse($result['success']);
    }

    public function test_verify_token_success()
    {
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $found = $this->authService->verifyToken('tokentest');
        $this->assertEquals($user->id, $found->id);
    }

    public function test_verify_token_expired()
    {
        User::factory()->create([
            'api_token' => 'expiredtoken',
            'token_expires_at' => now()->subHour()
        ]);
        $found = $this->authService->verifyToken('expiredtoken');
        $this->assertNull($found);
    }

    public function test_refresh_token_success()
    {
        $user = User::factory()->create([
            'api_token' => 'oldtoken',
            'token_expires_at' => now()->addHour()
        ]);
        $result = $this->authService->refreshToken('oldtoken');
        $this->assertTrue($result['success']);
        $this->assertNotEquals('oldtoken', $result['token']);
    }

    public function test_register_patient_success()
    {
        $data = [
            'name' => 'Test Patient',
            'email' => 'patient@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient',
            'NIK' => '1234567890'
        ];
        $result = $this->authService->register($data);
        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', ['email' => 'patient@example.com']);
    }

    public function test_register_doctor_success()
    {
        $data = [
            'name' => 'Test Doctor',
            'email' => 'doctor@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'doctor',
            'str_number' => 'STR123',
            'full_name' => 'Dr. Test',
            'specialist' => 'Cardiology',
            'polyclinic' => 'Heart'
        ];
        $result = $this->authService->register($data);
        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('users', ['email' => 'doctor@example.com']);
    }

    public function test_is_authenticated_with_token()
    {
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $this->assertTrue($this->authService->isAuthenticated('tokentest'));
    }

    public function test_check_role_admin_backdoor()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'api_token' => 'admintoken',
            'token_expires_at' => now()->addHour()
        ]);
        $this->assertTrue($this->authService->checkRole('doctor', 'admintoken'));
    }

    public function test_check_email_exists()
    {
        User::factory()->create(['email' => 'exists@example.com']);
        $this->assertTrue($this->authService->checkEmailExists('exists@example.com'));
        $this->assertFalse($this->authService->checkEmailExists('notfound@example.com'));
    }

    public function test_change_password()
    {
        $user = User::factory()->create(['password' => 'oldpass']);
        $this->authService->changePassword($user->id, 'oldpass', 'newpass');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'password' => 'newpass']);
    }

    public function test_logout()
    {
        $user = User::factory()->create(['api_token' => 'tokentest']);
        Session::put('user_id', $user->id);
        $result = $this->authService->logout('tokentest');
        $this->assertTrue($result['success']);
        $this->assertNull(User::find($user->id)->api_token);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient',
            'NIK' => '1234567890'
        ]);
        $response->assertStatus(201)
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);
        $response = $this->postJson('/api/auth/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['token']);
    }

    public function test_login_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'notfound@example.com',
            'password' => 'wrongpass',
            'role' => 'patient'
        ]);
        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_current_user_success()
    {
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->get('/api/auth/user');
        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['user']);
    }

    public function test_current_user_unauthenticated()
    {
        $response = $this->get('/api/auth/user');
        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_logout_success()
    {
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->post('/api/auth/logout');
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_refresh_token_success()
    {
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->post('/api/auth/token/refresh');
        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['token']);
    }

    public function test_refresh_token_invalid()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalidtoken'
        ])->post('/api/auth/token/refresh');
        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_verify_token_success()
    {
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->get('/api/auth/token/verify');
        $response->assertStatus(200)
            ->assertJson(['success' => true, 'valid' => true]);
    }

    public function test_verify_token_invalid()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalidtoken'
        ])->get('/api/auth/token/verify');
        $response->assertStatus(401)
            ->assertJson(['success' => false, 'valid' => false]);
    }

    public function test_check_email_exists()
    {
        User::factory()->create(['email' => 'exists@example.com']);
        $response = $this->postJson('/api/auth/email/check', [
            'email' => 'exists@example.com'
        ]);
        $response->assertStatus(200)
            ->assertJson(['exists' => true]);
    }

    public function test_check_email_not_exists()
    {
        $response = $this->postJson('/api/auth/email/check', [
            'email' => 'notfound@example.com'
        ]);
        $response->assertStatus(200)
            ->assertJson(['exists' => false]);
    }

    public function test_change_password_success()
    {
        $user = User::factory()->create([
            'password' => 'oldpass',
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->post('/api/auth/password/change', [
                    'old_password' => 'oldpass',
                    'new_password' => 'newpass'
                ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_update_profile_success()
    {
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->post('/api/auth/profile/update', [
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com',
                    'role' => 'admin'
                ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_reset_password_success()
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => 'oldpass'
        ]);
        $response = $this->postJson('/api/auth/password/reset', [
            'email' => 'reset@example.com',
            'new_password' => 'newpass'
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}

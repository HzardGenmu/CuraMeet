<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $doctor;
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat user dan doctor
        $this->user = User::factory()->create(['role' => 'doctor']);
        $this->doctor = Doctor::factory()->create(['user_id' => $this->user->id]);
        // Simulasi token (atau sesuaikan dengan AuthService-mu)
        $this->token = 'testtoken';
        // Jika pakai AuthService, mock verifyToken agar return $this->user
        $this->mock(\App\Services\AuthService::class, function ($mock) {
            $mock->shouldReceive('verifyToken')->andReturn($this->user);
        });
    }

    public function test_get_doctor_now_success()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/doctors/profile/now');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'doctor' => [
                    'user_id' => $this->user->id,
                    'full_name' => $this->doctor->full_name,
                ]
            ]);
    }

    public function test_get_doctor_by_id_success()
    {
        $response = $this->get('/api/doctors/' . $this->doctor->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'doctor' => [
                    'id' => $this->doctor->id,
                ]
            ]);
    }

    public function test_get_doctor_by_user_id_success()
    {
        $response = $this->get('/api/doctors/user/' . $this->user->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'doctor' => [
                    'user_id' => $this->user->id,
                ]
            ]);
    }

    public function test_get_doctors_by_name()
    {
        Doctor::factory()->create(['full_name' => 'Dr. Jane Smith']);
        $response = $this->get('/api/doctors/search?name=Jane');

        $this->assertTrue(
            collect($response->json('doctors'))->contains(function ($doctor) {
                return str_contains($doctor['full_name'], 'Jane');
            })
        );
    }

    public function test_list_doctors()
    {
        Doctor::factory()->count(2)->create();
        $response = $this->get('/api/doctors/');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
        $this->assertGreaterThanOrEqual(1, $response->json('count'));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_patient_now_success()
    {
        $user = User::factory()->create(['api_token' => 'tokentest', 'token_expires_at' => now()->addHour()]);
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->get('/api/patients/profile/now');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['patient']);
    }

    public function test_get_patient_now_token_not_provided()
    {
        $response = $this->get('/api/patients/profile/now');
        $response->assertStatus(401)
            ->assertJson(['success' => false, 'message' => 'Token not provided']);
    }

    public function test_get_patient_now_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalidtoken'
        ])->get('/api/patients/profile/now');
        $response->assertStatus(401)
            ->assertJson(['success' => false, 'message' => 'Invalid or expired token']);
    }

    public function test_get_patient_by_user_id_success()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $response = $this->get('/api/patients/user/' . $user->id);
        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['patient']);
    }

    public function test_get_patient_by_user_id_not_found()
    {
        $response = $this->get('/api/patients/user/9999');
        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Patient not found']);
    }

    public function test_get_patient_by_id_success()
    {
        $patient = Patient::factory()->create();
        $response = $this->get('/api/patients/' . $patient->id);
        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['patient']);
    }

    public function test_get_patient_by_id_not_found()
    {
        $response = $this->get('/api/patients/9999');
        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Patient not found']);
    }

    public function test_get_patients_by_name_success()
    {
        $patient = Patient::factory()->create(['full_name' => 'John Doe']);
        $response = $this->get('/api/patients/search?name=John');
        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['patients', 'count']);
    }

    public function test_isi_form_data_diri_success()
    {
        // Buat user dan patient yang saling terhubung
        $user = User::factory()->create([
            'api_token' => 'tokentest',
            'token_expires_at' => now()->addHour()
        ]);
        $patient = Patient::factory()->create([
            'user_id' => $user->id
        ]);

        // Jika controller cek $user->patientId, pastikan User model ada accessor patientId:
        // public function getPatientIdAttribute() { return optional($this->patient)->id; }
        // atau pastikan field yang dicek di controller sesuai

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '08123456789',
            'address' => 'Jl. Baru',
            'nik' => '1234567890123456'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer tokentest'
        ])->post('/api/patients/' . $patient->id . '/profile/fill', $data);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_isi_form_data_diri_unauthorized()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '08123456789',
            'address' => 'Jl. Baru',
            'nik' => '1234567890123456'
        ];

        $response = $this->post('/api/patients/' . $patient->id . '/profile/fill', $data);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    // public function test_lihat_statistik_success()
    // {
    //     $patient = Patient::factory()->create();
    //     $response = $this->get('/api/patient/' . $patient->id . '/statistics');
    //     $response->assertStatus(200)
    //         ->assertJson(['success' => true])
    //         ->assertJsonStructure(['statistics']);
    // }
}

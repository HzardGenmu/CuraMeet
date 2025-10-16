<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Hash;

class ApiRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;
    protected $patient;
    protected $doctor;
    protected $doctorUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Patient user
        $this->user = User::factory()->create([
            'name' => 'Test Patient',
            'email' => 'user@test.com',
            'password' => Hash::make('password123'),
            'role' => 'patient'
        ]);

        $this->patient = Patient::factory()->create([
            'user_id' => $this->user->id,
            'NIK' => '1234567890123456',
            'full_name' => 'Test Patient',
            'picture' => 'patient.jpg',
            'allergies' => 'None',
            'disease_histories' => 'None'
        ]);

        // Doctor user
        $this->doctorUser = User::factory()->create([
            'name' => 'Test Doctor',
            'email' => 'doctor@test.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor'
        ]);

        $this->doctor = Doctor::factory()->create([
            'user_id' => $this->doctorUser->id,
            'str_number' => 'STR123456',
            'full_name' => 'Test Doctor',
            'specialist' => 'Cardiology',
            'polyclinic' => 'Heart',
            'available_time' => '08:00-16:00'
        ]);

        // Admin user
        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);
    }
    public function test_patient_factory_creates_data()
    {
        $this->assertDatabaseHas('patients', [
            'NIK' => '1234567890123456',
            'full_name' => 'Test Patient'
        ]);
    }

    public function test_doctor_factory_creates_data()
    {
        $this->assertDatabaseHas('doctors', [
            'str_number' => 'STR123456',
            'full_name' => 'Test Doctor'
        ]);
    }

    public function test_user_factory_creates_data()
    {
        $this->assertDatabaseHas('users', [
            'email' => 'user@test.com',
            'role' => 'patient'
        ]);

        // Ambil user dari database
        $user = User::where('email', 'user@test.com')->first();
        $this->assertNotNull($user);

        // Cek password (harus di-hash)
        $this->assertTrue(\Hash::check('password123', $user->password));
    }
    /**
     * Helper untuk login dan ambil token
     */
    protected function loginAndGetToken($email, $password, $role)
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => $email,
            'password' => $password,
            'role' => $role
        ]);

        $data = $response->json();

        if (!isset($data['success']) || !$data['success']) {
            $this->fail('Login failed: ' . json_encode($data));
        }

        return $data['token'];
    }

    //=========================================================
    // Authentication Routes Tests
    //=========================================================
    public function test_auth_routes_are_accessible()
    {
        // Test tanpa credentials - should return 401 or 422
        $this->postJson('/api/auth/login', [])->assertStatus(401);

        // Register without data - validation error
        $this->postJson('/api/auth/register', [])->assertStatus(422);

        // Password reset endpoints
        $this->postJson('/api/auth/password/reset', [])->assertStatus(200);
        $this->postJson('/api/auth/email/check', [])->assertStatus(200);

        // Token verify without token
        $this->getJson('/api/auth/token/verify')->assertStatus(401);

        // Login dengan kredensial valid
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // Akses rute user dengan token
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/auth/user')->assertStatus(200);
    }

    //=========================================================
    // Doctor Routes Tests
    //=========================================================
    public function test_doctor_routes_are_accessible()
    {
        // Rute publik
        $this->getJson('/api/doctors')->assertStatus(200);
        $this->getJson('/api/doctors/search?name=test')->assertStatus(200);
        $this->getJson("/api/doctors/{$this->doctor->id}")->assertStatus(200);

        // Login user dan dapat token
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // Akses rute dengan token
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/doctors/profile/now')->assertStatus(200);
    }

    //=========================================================
    // Patient Routes Tests
    //=========================================================
    public function test_patient_routes_are_accessible()
    {
        // Public routes
        $this->getJson('/api/patients/search?name=test')->assertStatus(200);
        $this->getJson("/api/patients/{$this->patient->id}")->assertStatus(200);

        // Fill profile with required data
        $this->postJson("/api/patients/{$this->patient->id}/profile/fill", [
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'phone' => '081234567890',
            'address' => 'Test Address',
            'nik' => '1234567890123456'
        ])->assertStatus(200);

        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/patients/profile/now')->assertStatus(200);
    }

    //=========================================================
    // Appointment Routes Tests
    //=========================================================
    public function test_appointment_routes_are_accessible()
    {
        // Create appointment with proper data
        $this->postJson('/api/appointments/new', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'appointment_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'catatan' => 'Test appointment'
        ])->assertStatus(200);

        // Create an appointment to cancel
        $appointment = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $this->postJson("/api/appointments/{$appointment->id}/cancel", [
            'reason' => 'Test cancellation'
        ])->assertStatus(200);

        $this->getJson('/api/appointments/doctor')->assertStatus(200);
        $this->getJson('/api/appointments/patient')->assertStatus(200);
    }

    //=========================================================
    // Medical Record Routes Tests
    //=========================================================
    public function test_medical_record_routes_are_accessible()
    {
        // Create medical record
        $record = MedicalRecord::factory()->create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id
        ]);

        // Public route to view records
        $this->getJson("/api/medical-records/patient?patient_id={$record->patient_id}")
            ->assertStatus(200);

        // Protected routes without token
        $this->postJson('/api/medical-records/upload', [])->assertStatus(401);
        $this->postJson('/api/medical-records/update', [])->assertStatus(401);
        $this->deleteJson("/api/medical-records/{$record->id}/delete")->assertStatus(401);

        // Get token
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // With token but missing required fields - validation error
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/medical-records/upload', [])->assertStatus(422);

        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/medical-records/update', [])->assertStatus(422);

        // Delete with token
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson("/api/medical-records/{$record->id}/delete")->assertStatus(200);
    }

    //=========================================================
    // Admin Routes Tests
    //=========================================================
    public function test_admin_routes_are_protected()
    {
        // Tanpa token
        $this->postJson('/api/admin/roles/manage', [])->assertStatus(401);
        $this->getJson('/api/admin/logs/activity')->assertStatus(401);

        // User biasa
        $userToken = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $this->withHeaders(['Authorization' => 'Bearer ' . $userToken])
            ->postJson('/api/admin/roles/manage', [])->assertStatus(403);

        $this->withHeaders(['Authorization' => 'Bearer ' . $userToken])
            ->getJson('/api/admin/logs/activity')->assertStatus(403);

        // Admin
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $this->withHeaders(['Authorization' => 'Bearer ' . $adminToken])
            ->postJson('/api/admin/roles/manage', [
                'user_id' => $this->user->id,
                'new_role' => 'doctor',
                'admin_id' => $this->admin->id
            ])->assertStatus(200);

        $this->withHeaders(['Authorization' => 'Bearer ' . $adminToken])
            ->getJson('/api/admin/logs/activity')->assertStatus(200);
    }

    //=========================================================
    // Additional Route Coverage Tests
    //=========================================================
    public function test_vulnerable_doctor_routes()
    {
        // These routes should be protected but aren't
        $this->postJson('/api/doctors/medical-records/view', [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id
        ])->assertStatus(200);

        $this->postJson("/api/doctors/patients/{$this->patient->id}/export", [])
            ->assertStatus(200);
    }

    public function test_vulnerable_appointment_routes()
    {
        $appointment = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        // Vulnerable routes without proper authorization
        $this->postJson('/api/appointments/change-schedule/doctor', [
            'appointment_id' => $appointment->id,
            'new_time' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'doctor_id' => $this->doctor->id
        ])->assertStatus(200);

        $this->postJson('/api/appointments/cancel/doctor', [
            'appointment_id' => $appointment->id,
            'reason' => 'Doctor unavailable',
            'doctor_id' => $this->doctor->id
        ])->assertStatus(200);
    }

    public function test_medical_record_by_id()
    {
        $record = MedicalRecord::factory()->create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id
        ]);

        $this->getJson("/api/medical-records/{$record->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'record'
            ]);
    }
}

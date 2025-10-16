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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $adminUser;
    protected $patient;
    protected $doctor;
    protected $doctorUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create patient user (password will be stored as plain text for SQL injection testing)
        $this->user = User::create([
            'name' => 'Test Patient',
            'email' => 'user@test.com',
            'password' => 'password123', // Plain text for SQL injection test
            'role' => 'patient'
        ]);

        $this->patient = Patient::create([
            'user_id' => $this->user->id,
            'NIK' => '1234567890123456',
            'full_name' => 'Test Patient',
            'picture' => 'patient.jpg',
            'allergies' => 'None',
            'disease_histories' => 'None',
            'email' => 'user@test.com',
            'phone' => '081234567890',
            'address' => 'Test Address'
        ]);

        // Create doctor user
        $this->doctorUser = User::create([
            'name' => 'Test Doctor',
            'email' => 'doctor@test.com',
            'password' => 'password123', // Plain text
            'role' => 'doctor'
        ]);

        $this->doctor = Doctor::create([
            'user_id' => $this->doctorUser->id,
            'str_number' => 'STR123456',
            'full_name' => 'Test Doctor',
            'specialist' => 'Cardiology',
            'polyclinic' => 'Heart',
            'available_time' => '08:00-16:00'
        ]);

        // Create admin user
        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => 'password123', // Plain text
            'role' => 'admin'
        ]);
    }

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
    // AuthService Tests
    //=========================================================

    public function test_auth_login_successful()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'user' => ['id', 'name', 'email', 'role'],
                'token',
                'token_type',
                'expires_in'
            ]);
    }

    public function test_auth_login_failed_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'wrongpassword',
            'role' => 'patient'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    public function test_auth_register_patient()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'New Patient',
            'email' => 'newpatient@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'patient',
            'NIK' => '9876543210987654',
            'full_name' => 'New Patient Full',
            'picture' => 'picture.jpg',
            'allergies' => 'None',
            'disease_histories' => 'None'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_auth_register_doctor()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'New Doctor',
            'email' => 'newdoctor@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'doctor',
            'str_number' => 'STR999999',
            'full_name' => 'New Doctor Full',
            'specialist' => 'General',
            'polyclinic' => 'General',
            'available_time' => '09:00-17:00'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_auth_register_validation_fails()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test',
            'email' => 'invalid-email',
            'password' => '123'
        ]);

        $response->assertStatus(422);
    }

    public function test_auth_get_current_user()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/auth/user');

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email', 'role']);
    }

    public function test_auth_verify_token()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/auth/token/verify');

        $response->assertStatus(200);
    }

    public function test_auth_verify_token_invalid()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token'
        ])->getJson('/api/auth/token/verify');

        $response->assertStatus(401);
    }

    public function test_auth_logout()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_auth_password_reset()
    {
        $response = $this->postJson('/api/auth/password/reset', [
            'email' => 'user@test.com',
            'new_password' => 'newpassword123'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_auth_check_email_exists()
    {
        $response = $this->postJson('/api/auth/email/check', [
            'email' => 'user@test.com'
        ]);

        $response->assertStatus(200);
    }

    public function test_auth_check_email_not_exists()
    {
        $response = $this->postJson('/api/auth/email/check', [
            'email' => 'nonexistent@test.com'
        ]);

        $response->assertStatus(200);
    }

    //=========================================================
    // DoctorService Tests
    //=========================================================

    public function test_doctor_list_all()
    {
        $response = $this->getJson('/api/doctors');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'doctors',
                'count'
            ]);
    }

    public function test_doctor_search_by_name()
    {
        $response = $this->getJson('/api/doctors/search?name=Test');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'doctors',
                'count'
            ]);
    }

    public function test_doctor_search_by_name_empty_result()
    {
        $response = $this->getJson('/api/doctors/search?name=NonExistentDoctor');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'doctors',
                'count'
            ]);
    }

    public function test_doctor_get_by_id()
    {
        $response = $this->getJson("/api/doctors/{$this->doctor->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'doctor'
            ]);
    }

    public function test_doctor_get_by_id_not_found()
    {
        $response = $this->getJson("/api/doctors/99999");

        $response->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    public function test_doctor_get_profile_authenticated()
    {
        $token = $this->loginAndGetToken('doctor@test.com', 'password123', 'doctor');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/doctors/profile/now');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'doctor'
            ]);
    }

    public function test_doctor_get_profile_unauthenticated()
    {
        $response = $this->getJson('/api/doctors/profile/now');

        $response->assertStatus(401);
    }

    //=========================================================
    // PatientService Tests
    //=========================================================

    public function test_patient_search_by_name()
    {
        $response = $this->getJson('/api/patients/search?name=Test');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'patients',
                'count'
            ]);
    }

    public function test_patient_get_by_id()
    {
        $response = $this->getJson("/api/patients/{$this->patient->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'patient'
            ]);
    }

    public function test_patient_get_by_id_not_found()
    {
        $response = $this->getJson("/api/patients/99999");

        $response->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    public function test_patient_get_profile_authenticated()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/patients/profile/now');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'patient'
            ]);
    }

    public function test_patient_fill_profile_form()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/patients/{$this->patient->id}/profile/fill", [
                    'name' => 'Updated Patient',
                    'email' => 'updated@test.com',
                    'phone' => '081234567890',
                    'address' => 'Updated Address',
                    'nik' => '1234567890123456'
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_patient_fill_profile_unauthorized()
    {
        // Create another patient
        $anotherUser = User::create([
            'name' => 'Another Patient',
            'email' => 'another@test.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);

        $anotherPatient = Patient::create([
            'user_id' => $anotherUser->id,
            'NIK' => '9999999999999999',
            'full_name' => 'Another Patient'
        ]);

        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // Try to update another patient's profile
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/patients/{$anotherPatient->id}/profile/fill", [
                    'name' => 'Hacked Name',
                    'email' => 'hacked@test.com',
                    'phone' => '081111111111',
                    'address' => 'Hacked Address',
                    'nik' => '9999999999999999'
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Unauthorized']);
    }

    public function test_patient_view_statistics()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/patients/{$this->patient->id}/statistics?date_from=2020-01-01&date_to=2025-12-31");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'statistics'
            ]);
    }

    //=========================================================
    // AppointmentService Tests
    //=========================================================

    public function test_appointment_create_new()
    {
        $response = $this->postJson('/api/appointments/new', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'appointment_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'catatan' => 'Test appointment'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'appointment_id',
                'message'
            ]);
    }

    public function test_appointment_create_duplicate_time()
    {
        $appointmentTime = now()->addDay()->format('Y-m-d H:i:s');

        // Create first appointment
        Appointment::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => $appointmentTime,
            'status' => 'pending'
        ]);

        // Try to create another at same time
        $response = $this->postJson('/api/appointments/new', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'appointment_time' => $appointmentTime,
            'catatan' => 'Duplicate appointment'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    public function test_appointment_cancel_by_patient()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/appointments/{$appointment->id}/cancel", [
                    'reason' => 'Test cancellation'
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_appointment_cancel_unauthorized()
    {
        // Create another patient
        $anotherUser = User::create([
            'name' => 'Another Patient',
            'email' => 'another2@test.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);

        $anotherPatient = Patient::create([
            'user_id' => $anotherUser->id,
            'NIK' => '8888888888888888',
            'full_name' => 'Another Patient'
        ]);

        $appointment = Appointment::create([
            'patient_id' => $anotherPatient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // Try to cancel another patient's appointment
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/appointments/{$appointment->id}/cancel", [
                    'reason' => 'Unauthorized cancellation'
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Unauthorized']);
    }

    public function test_appointment_change_schedule_by_doctor()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->postJson('/api/appointments/change-schedule/doctor', [
            'appointment_id' => $appointment->id,
            'new_time' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'doctor_id' => $this->doctor->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_appointment_cancel_by_doctor()
    {
        $appointment = Appointment::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->postJson('/api/appointments/cancel/doctor', [
            'appointment_id' => $appointment->id,
            'reason' => 'Doctor unavailable',
            'doctor_id' => $this->doctor->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_appointment_get_by_doctor()
    {
        Appointment::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->getJson('/api/appointments/doctor?doctor_id=' . $this->doctor->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'appointments'
            ]);
    }

    public function test_appointment_get_by_patient()
    {
        Appointment::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'time_appointment' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);

        $response = $this->getJson('/api/appointments/patient?patient_id=' . $this->patient->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'appointments'
            ]);
    }

    //=========================================================
    // MedicalRecordService Tests
    //=========================================================

    public function test_medical_record_upload()
    {
        Storage::fake('public');
        $token = $this->loginAndGetToken('doctor@test.com', 'password123', 'doctor');

        $file = UploadedFile::fake()->create('test.pdf', 1024);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/medical-records/upload', [
                    'patient_id' => $this->patient->id,
                    'doctor_id' => $this->doctor->id,
                    'file' => $file,
                    'doctor_note' => 'Test note'
                ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'file_path',
                'record_id'
            ]);
    }

    public function test_medical_record_upload_invalid_file_type()
    {
        Storage::fake('public');
        $token = $this->loginAndGetToken('doctor@test.com', 'password123', 'doctor');

        $file = UploadedFile::fake()->create('test.exe', 1024);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/medical-records/upload', [
                    'patient_id' => $this->patient->id,
                    'doctor_id' => $this->doctor->id,
                    'file' => $file,
                    'doctor_note' => 'Test note'
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'File type not allowed']);
    }

    public function test_medical_record_upload_file_too_large()
    {
        Storage::fake('public');
        $token = $this->loginAndGetToken('doctor@test.com', 'password123', 'doctor');

        $file = UploadedFile::fake()->create('test.pdf', 3072); // 3MB

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/medical-records/upload', [
                    'patient_id' => $this->patient->id,
                    'doctor_id' => $this->doctor->id,
                    'file' => $file,
                    'doctor_note' => 'Test note'
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'File too large']);
    }

    public function test_medical_record_get_by_patient()
    {
        MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Test Disease',
            'catatan_dokter' => 'Test note'
        ]);

        $response = $this->getJson("/api/medical-records/patient?patient_id={$this->patient->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'records'
            ]);
    }

    public function test_medical_record_get_by_doctor()
    {
        MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Test Disease',
            'catatan_dokter' => 'Test note'
        ]);

        $response = $this->postJson('/api/doctors/medical-records/view', [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'records'
            ]);
    }

    public function test_medical_record_get_by_id()
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Test Disease',
            'catatan_dokter' => 'Test note'
        ]);

        $response = $this->getJson("/api/medical-records/{$record->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'record'
            ]);
    }

    public function test_medical_record_update()
    {
        $token = $this->loginAndGetToken('doctor@test.com', 'password123', 'doctor');

        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Test Disease',
            'catatan_dokter' => 'Test note'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/medical-records/update', [
                    'medical_record_id' => $record->id,
                    'disease_name' => 'Updated Disease',
                    'catatan_dokter' => 'Updated note'
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_medical_record_delete()
    {
        $token = $this->loginAndGetToken('doctor@test.com', 'password123', 'doctor');

        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Test Disease',
            'catatan_dokter' => 'Test note'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/medical-records/{$record->id}/delete");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    //=========================================================
    // AdminService Tests
    //=========================================================

    public function test_admin_manage_role_unauthorized()
    {
        $userToken = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $userToken
        ])->postJson('/api/admin/roles/manage', [
                    'user_id' => $this->user->id,
                    'new_role' => 'admin',
                    'admin_id' => $this->user->id
                ]);

        $response->assertStatus(403);
    }

    public function test_admin_manage_role_authorized()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->postJson('/api/admin/roles/manage', [
                    'user_id' => $this->user->id,
                    'new_role' => 'doctor',
                    'admin_id' => $this->adminUser->id
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_admin_view_activity_logs()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/admin/logs/activity');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'logs'
            ]);
    }

    public function test_admin_bulk_role_management()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->postJson('/api/admin/roles/bulk', [
                    'operations' => [
                        [
                            'user_id' => $this->user->id,
                            'role' => 'doctor',
                            'action' => 'update'
                        ]
                    ]
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_admin_audit_logs()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/admin/audit/logs?table=users&action=update');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'audit_logs'
            ]);
    }

    public function test_admin_api_request_logs()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/admin/logs/api?endpoint=/api/auth/login&method=POST');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'api_logs'
            ]);
    }

    public function test_admin_system_monitoring()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/admin/system/monitor');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'system_info'
            ]);
    }

    public function test_admin_traffic_anomaly_detection()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/admin/traffic/anomalies?threshold=100');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'anomalies'
            ]);
    }

    public function test_admin_system_maintenance()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->postJson('/api/admin/system/maintenance', [
                    'operation' => 'clear_logs',
                    'parameters' => []
                ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    //=========================================================
    // Security Vulnerability Tests (Demonstrating vulnerabilities)
    //=========================================================

    public function test_sql_injection_in_login()
    {
        // Test SQL injection vulnerability - this demonstrates the vulnerability
        $response = $this->postJson('/api/auth/login', [
            'email' => "admin@test.com' OR '1'='1",
            'password' => "' OR '1'='1",
            'role' => 'admin'
        ]);

        // With SQL injection vulnerability, this might succeed
        $response->assertStatus(200);
    }

    public function test_unauthorized_access_to_medical_records()
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Confidential',
            'catatan_dokter' => 'Secret note'
        ]);

        // Try to access without authentication - demonstrates vulnerability
        $response = $this->getJson("/api/medical-records/patient?patient_id={$this->patient->id}");

        // Vulnerability: Should be protected but returns 200
        $response->assertStatus(200);

        // Check if sensitive data is exposed (including password)
        $data = $response->json();
        if (isset($data['records']) && count($data['records']) > 0) {
            $this->assertArrayHasKey('password', $data['records'][0]);
        }
    }

    public function test_privilege_escalation_attempt()
    {
        $userToken = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // Patient trying to access admin functionality
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $userToken
        ])->postJson('/api/admin/roles/manage', [
                    'user_id' => $this->user->id,
                    'new_role' => 'admin',
                    'admin_id' => $this->user->id
                ]);

        // Should return 403 Forbidden
        $response->assertStatus(403);
    }

    public function test_sql_injection_in_admin_logs()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        // SQL injection in activity logs filter
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson("/api/admin/logs/activity?user_id=1' OR '1'='1&action=login' OR '1'='1");

        // Demonstrates SQL injection vulnerability
        $response->assertStatus(200);
    }

    public function test_information_disclosure_in_role_management()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->postJson('/api/admin/roles/manage', [
                    'user_id' => $this->user->id,
                    'new_role' => 'doctor',
                    'admin_id' => $this->adminUser->id
                ]);

        $response->assertStatus(200);

        // Check if password hash is exposed
        $data = $response->json();
        if (isset($data['user_info'])) {
            $this->assertObjectHasProperty('password', (object) $data['user_info']);
        }
    }

    public function test_command_injection_in_system_monitoring()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        // Demonstrates command injection vulnerability
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/admin/system/monitor');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'system_info' => [
                    'cpu_usage',
                    'memory_usage',
                    'disk_usage'
                ]
            ]);
    }

    public function test_no_rate_limiting_on_login()
    {
        // Demonstrate no rate limiting - can make unlimited login attempts
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson('/api/auth/login', [
                'email' => 'user@test.com',
                'password' => 'wrongpassword' . $i,
                'role' => 'patient'
            ]);

            $response->assertStatus(200);
        }

        // All attempts should succeed (return 200), demonstrating no rate limiting
        $this->assertTrue(true);
    }

    public function test_weak_password_reset()
    {
        // Demonstrates weak password reset with default password
        $response = $this->postJson('/api/auth/password/reset', [
            'email' => 'user@test.com'
            // No new_password provided - will use default 'temp123'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Check if password was reset to weak default
        $this->assertDatabaseHas('users', [
            'email' => 'user@test.com',
            'password' => 'temp123'
        ]);
    }

    public function test_user_enumeration_vulnerability()
    {
        // Check existing email
        $response1 = $this->postJson('/api/auth/email/check', [
            'email' => 'user@test.com'
        ]);

        // Check non-existing email
        $response2 = $this->postJson('/api/auth/email/check', [
            'email' => 'nonexistent@test.com'
        ]);

        // Both should return 200, but might reveal user existence
        $response1->assertStatus(200);
        $response2->assertStatus(200);
    }

    public function test_idor_in_patient_export()
    {
        // Create another patient
        $anotherUser = User::create([
            'name' => 'IDOR Test Patient',
            'email' => 'idor@test.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);

        $anotherPatient = Patient::create([
            'user_id' => $anotherUser->id,
            'NIK' => '7777777777777777',
            'full_name' => 'IDOR Test Patient'
        ]);

        // Try to export another patient's data without authorization
        $response = $this->postJson("/api/doctors/patients/{$anotherPatient->id}/export", []);

        // Vulnerability: Should be protected but returns 200
        $response->assertStatus(200);
    }

    public function test_xss_in_appointment_notes()
    {
        $xssPayload = '<script>alert("XSS")</script>';

        $response = $this->postJson('/api/appointments/new', [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'appointment_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'catatan' => $xssPayload
        ]);

        $response->assertStatus(200);

        // Check if XSS payload was stored without sanitization
        $this->assertDatabaseHas('appointments', [
            'catatan' => $xssPayload
        ]);
    }

    public function test_mass_assignment_vulnerability()
    {
        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // Try to change role via mass assignment
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/profile/update', [
                    'user_id' => $this->user->id,
                    'name' => 'Updated Name',
                    'email' => 'newemail@test.com',
                    'role' => 'admin' // Trying to escalate privilege
                ]);

        // Check if role was changed (vulnerability)
        $response->assertStatus(200);
    }

    public function test_exposed_database_credentials_in_audit()
    {
        $adminToken = $this->loginAndGetToken('admin@test.com', 'password123', 'admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken
        ])->getJson('/api/admin/audit/logs');

        $response->assertStatus(200);

        // Check if database credentials are exposed
        $data = $response->json();
        if (isset($data['database_info'])) {
            $this->assertArrayHasKey('host', $data['database_info']);
            $this->assertArrayHasKey('database', $data['database_info']);
            $this->assertArrayHasKey('username', $data['database_info']);
        }
    }

    public function test_timing_attack_vulnerability()
    {
        // Measure response time for existing user
        $start1 = microtime(true);
        $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'wrongpassword',
            'role' => 'patient'
        ]);
        $time1 = microtime(true) - $start1;

        // Measure response time for non-existing user
        $start2 = microtime(true);
        $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
            'role' => 'patient'
        ]);
        $time2 = microtime(true) - $start2;

        // Both should complete (might have timing differences)
        $this->assertTrue(true);
    }

    public function test_insecure_direct_object_reference_medical_records()
    {
        // Create two patients with medical records
        $patient2User = User::create([
            'name' => 'Patient 2',
            'email' => 'patient2@test.com',
            'password' => 'password123',
            'role' => 'patient'
        ]);

        $patient2 = Patient::create([
            'user_id' => $patient2User->id,
            'NIK' => '6666666666666666',
            'full_name' => 'Patient 2'
        ]);

        $record1 = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Patient 1 Disease',
            'catatan_dokter' => 'Confidential note 1'
        ]);

        $record2 = MedicalRecord::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $this->doctor->id,
            'disease_name' => 'Patient 2 Disease',
            'catatan_dokter' => 'Confidential note 2'
        ]);

        $token = $this->loginAndGetToken('user@test.com', 'password123', 'patient');

        // Try to access another patient's medical record
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/medical-records/{$record2->id}");

        // Vulnerability: Should be protected but returns 200
        $response->assertStatus(200);
    }
}

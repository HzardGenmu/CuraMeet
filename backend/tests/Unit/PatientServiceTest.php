<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $patientService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->patientService = app(PatientService::class);
    }

    public function test_get_patient_by_user_id_returns_patient()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        $result = $this->patientService->getPatientByUserId($user->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($patient->id, $result['patient']->id);
    }

    public function test_get_patient_by_id_returns_patient()
    {
        $patient = Patient::factory()->create();

        $result = $this->patientService->getPatientById($patient->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($patient->id, $result['patient']->id);
    }

    public function test_get_patients_by_name_returns_matching_patients()
    {
        Patient::factory()->create(['full_name' => 'John Doe']);
        Patient::factory()->create(['full_name' => 'Jane Doe']);

        $result = $this->patientService->getPatientsByName('Jane');

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['count']);
        $this->assertEquals('Jane Doe', $result['patients'][0]->full_name);
    }

    public function test_isi_form_data_diri_updates_patient_and_user()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@email.com',
            'nik' => '1234567890'
        ];

        $result = $this->patientService->isiFormDataDiri($patient->id, $data, $user);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'full_name' => 'Updated Name',
            'NIK' => '1234567890'
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@email.com'
        ]);
    }

    public function test_isi_form_data_diri_unauthorized()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        $data = ['name' => 'Hacker'];

        $result = $this->patientService->isiFormDataDiri($patient->id, $data, $otherUser);

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized', $result['message']);
    }

    public function test_isi_form_data_diri_patient_not_found()
    {
        $user = User::factory()->create();

        $result = $this->patientService->isiFormDataDiri(9999, ['name' => 'Test'], $user);

        $this->assertFalse($result['success']);
        $this->assertEquals('Patient not found', $result['message']);
    }
}

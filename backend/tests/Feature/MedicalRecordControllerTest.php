<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\MedicalRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MedicalRecordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_rekam_medis_success()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();

        $file = UploadedFile::fake()->create('rekam.pdf', 100, 'application/pdf');

        // Autentikasi user
        $this->actingAs($user);

        $response = $this->post('/api/medical-records/upload', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'file' => $file,
            'doctor_note' => 'Catatan dokter'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_upload_rekam_medis_unauthorized()
    {
        Storage::fake('public');
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        $file = UploadedFile::fake()->create('rekam.pdf', 100, 'application/pdf');

        $response = $this->post('/api/medical-records/upload', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'file' => $file,
            'doctor_note' => 'Catatan dokter'
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_get_rekam_medis_by_patient()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        MedicalRecord::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);

        $response = $this->get('/api/medical-records/patient?patient_id=' . $patient->id);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'patient_id' => $patient->id]);
    }

    public function test_get_rekam_medis_by_id_success()
    {
        $user = User::factory()->create();
        $record = MedicalRecord::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/api/medical-records/' . $record->id);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }


    public function test_get_rekam_medis_by_id_unauthorized()
    {
        $record = MedicalRecord::factory()->create();

        $response = $this->get('/api/medical-records/' . $record->id);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_update_rekam_medis_success()
    {
        $user = User::factory()->create();
        $record = MedicalRecord::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/api/medical-records/update', [
            'id' => $record->id,
            'doctor_note' => 'Updated note',
            'disease_name' => 'Updated disease'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_update_rekam_medis_unauthorized()
    {
        $record = MedicalRecord::factory()->create();

        $response = $this->post('/api/medical-records/update', [
            'id' => $record->id,
            'doctor_note' => 'Updated note'
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_delete_rekam_medis_success()
    {
        $record = MedicalRecord::factory()->create();

        $response = $this->delete('/api/medical-records/' . $record->id . '/delete');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_delete_rekam_medis_not_found()
    {
        $response = $this->delete('/api/medical-records/9999/delete');

        $response->assertStatus(200)
            ->assertJson(['success' => false]);
    }
}

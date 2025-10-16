<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Services\MedicalRecordService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MedicalRecordServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $medicalRecordService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->medicalRecordService = app(MedicalRecordService::class);
    }

    public function test_upload_rekam_medis_success()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();

        $file = UploadedFile::fake()->create('rekam.pdf', 100, 'application/pdf');
        $result = $this->medicalRecordService->uploadRekamMedis($patient->id, $doctor->id, $file, 'Catatan dokter');

        $this->assertTrue($result['success']);
        $this->assertTrue(Storage::disk('public')->exists($result['file_path']));
        $this->assertDatabaseHas('medical_records', [
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'catatan_dokter' => 'Catatan dokter'
        ]);
    }

    public function test_upload_rekam_medis_invalid_file_type()
    {
        Storage::fake('public');
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();

        $file = UploadedFile::fake()->create('rekam.exe', 100, 'application/octet-stream');
        $result = $this->medicalRecordService->uploadRekamMedis($patient->id, $doctor->id, $file);

        $this->assertFalse($result['success']);
        $this->assertEquals('File type not allowed', $result['message']);
    }

    public function test_upload_rekam_medis_too_large()
    {
        Storage::fake('public');
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();

        $file = UploadedFile::fake()->create('rekam.pdf', 3000, 'application/pdf'); // >2MB
        $result = $this->medicalRecordService->uploadRekamMedis($patient->id, $doctor->id, $file);

        $this->assertFalse($result['success']);
        $this->assertEquals('File too large', $result['message']);
    }

    public function test_get_rekam_medis_by_patient()
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $record = MedicalRecord::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);

        $result = $this->medicalRecordService->getRekamMedisByPatient($patient->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($patient->id, $result['patient_id']);
        $this->assertNotEmpty($result['records']);
    }

    public function test_get_rekam_medis_by_doctor()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        $record = MedicalRecord::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);

        $result = $this->medicalRecordService->getRekamMedisByDoctor($doctor->id);

        $this->assertTrue($result['success']);
        $this->assertGreaterThanOrEqual(1, $result['total_records']);
    }

    public function test_get_rekam_medis_by_id()
    {
        $record = MedicalRecord::factory()->create();
        $result = $this->medicalRecordService->getRekamMedisById($record->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($record->id, $result['record']->id);
    }

    public function test_get_rekam_medis_by_id_not_found()
    {
        $result = $this->medicalRecordService->getRekamMedisById(9999);
        $this->assertFalse($result['success']);
        $this->assertEquals('Medical record not found', $result['message']);
    }

    public function test_update_rekam_medis()
    {
        $record = MedicalRecord::factory()->create(['catatan_dokter' => 'Lama']);
        $result = $this->medicalRecordService->updateRekamMedis($record->id, ['catatan_dokter' => 'Baru']);

        $this->assertTrue($result['success']);
        $this->assertEquals('Baru', $result['record']->catatan_dokter);
    }

    public function test_update_rekam_medis_not_found()
    {
        $result = $this->medicalRecordService->updateRekamMedis(9999, ['catatan_dokter' => 'Baru']);
        $this->assertFalse($result['success']);
        $this->assertEquals('Medical record not found', $result['message']);
    }

    public function test_hapus_rekam_medis()
    {
        Storage::fake('public');
        $record = MedicalRecord::factory()->create([
            'path_file' => 'uploads/rekam_medis/testfile.pdf'
        ]);
        Storage::disk('public')->put('uploads/rekam_medis/testfile.pdf', 'dummy content');

        $result = $this->medicalRecordService->hapusRekamMedis($record->id);

        $this->assertTrue($result['success']);
        $this->assertFalse(Storage::disk('public')->exists('uploads/rekam_medis/testfile.pdf'));
        $this->assertDatabaseMissing('medical_records', ['id' => $record->id]);
    }

    public function test_hapus_rekam_medis_not_found()
    {
        $result = $this->medicalRecordService->hapusRekamMedis(9999);
        $this->assertFalse($result['success']);
        $this->assertEquals('Medical record not found', $result['message']);
    }
}

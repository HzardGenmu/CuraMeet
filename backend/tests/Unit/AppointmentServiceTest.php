<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class AppointmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $appointmentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->appointmentService = app(AppointmentService::class);
    }

    public function test_new_appointment_success()
    {
        $doctor = Doctor::factory()->create();
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        $time = now()->addDay()->format('Y-m-d H:i:s');

        $result = $this->appointmentService->newAppointment($patient->id, $doctor->id, $time, 'Catatan pasien');

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('appointment_id', $result);
        $this->assertEquals('Pengecekan berhasil didaftarkan', $result['message']);
        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'time_appointment' => $time,
            'patient_note' => 'Catatan pasien',
            'status' => 'pending',
            'doctor_note' => 'Tidak ada',
            'cancelled_by' => null
        ]);
    }

    public function test_new_appointment_doctor_not_available()
    {
        $doctor = Doctor::factory()->create();
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        $time = now()->addDay()->format('Y-m-d H:i:s');

        // Buat appointment yang sudah ada di waktu yang sama
        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'time_appointment' => $time
        ]);

        $result = $this->appointmentService->newAppointment($patient->id, $doctor->id, $time, 'Catatan pasien');

        $this->assertFalse($result['success']);
        $this->assertEquals('Doctor not available at this time', $result['message']);
    }

    public function test_cancel_appointment_success()
    {
        $user = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);

        Auth::shouldReceive('id')->andReturn($user->id);

        $result = $this->appointmentService->cancelAppointment($appointment->id, 'Alasan batal');

        $this->assertTrue($result['success']);
        $this->assertEquals('cancelled', $result['cancelled_appointment']->status);
        $this->assertEquals('patient', $result['cancelled_appointment']->cancelled_by);
        $this->assertEquals('Alasan batal', $result['reason']);
    }

    public function test_cancel_appointment_unauthorized()
    {
        $user = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);

        Auth::shouldReceive('id')->andReturn(9999); // user_id salah

        $result = $this->appointmentService->cancelAppointment($appointment->id, 'Alasan batal');

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized', $result['message']);
    }

    public function test_cancel_appointment_not_found()
    {
        $user = User::factory()->patient()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $result = $this->appointmentService->cancelAppointment(9999, 'Alasan batal');

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized', $result['message']);
    }

    public function test_change_schedule_by_doctor_success()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);
        $newTime = now()->addDays(2)->format('Y-m-d H:i:s');

        $result = $this->appointmentService->changeScheduleByDoctor($appointment->id, $newTime, $doctor->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($newTime, $result['updated_appointment']->time_appointment);
        $this->assertEquals($newTime, $result['new_time']);
    }

    public function test_change_schedule_by_doctor_unauthorized()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);
        $newTime = now()->addDays(2)->format('Y-m-d H:i:s');

        $result = $this->appointmentService->changeScheduleByDoctor($appointment->id, $newTime, 9999);

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized or appointment not found', $result['message']);
    }

    public function test_cancel_appointment_by_doctor_success()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);

        $result = $this->appointmentService->cancelAppointmentByDoctor($appointment->id, 'Alasan dokter', $doctor->id);

        $this->assertTrue($result['success']);
        $this->assertEquals('doctor', $result['cancelled_appointment']->cancelled_by);
        $this->assertEquals('cancelled', $result['cancelled_appointment']->status);
        $this->assertEquals('Alasan dokter', $result['reason']);
    }

    public function test_cancel_appointment_by_doctor_unauthorized()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);

        $result = $this->appointmentService->cancelAppointmentByDoctor($appointment->id, 'Alasan dokter', 9999);

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized or appointment not found', $result['message']);
    }

    public function test_get_appointments_by_doctor()
    {
        $doctor = Doctor::factory()->create();
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        Appointment::factory()->count(3)->create(['doctor_id' => $doctor->id, 'patient_id' => $patient->id]);

        $result = $this->appointmentService->getAppointmentsByDoctor($doctor->id);

        $this->assertTrue($result['success']);
        $this->assertCount(3, $result['appointments']);
    }

    public function test_get_appointments_by_doctor_empty()
    {
        $doctor = Doctor::factory()->create();

        $result = $this->appointmentService->getAppointmentsByDoctor($doctor->id);

        $this->assertTrue($result['success']);
        $this->assertCount(0, $result['appointments']);
    }

    public function test_change_appointment_by_patient_success()
    {
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);
        $newTime = now()->addDays(3)->format('Y-m-d H:i:s');

        $result = $this->appointmentService->changeAppointmentByPatient($appointment->id, $newTime, $patient->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($newTime, $result['updated_appointment']->time_appointment);
        $this->assertEquals($newTime, $result['new_time']);
    }

    public function test_change_appointment_by_patient_unauthorized()
    {
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);
        $newTime = now()->addDays(3)->format('Y-m-d H:i:s');

        $result = $this->appointmentService->changeAppointmentByPatient($appointment->id, $newTime, 9999);

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized or appointment not found', $result['message']);
    }

    public function test_get_appointments_by_patient()
    {
        $doctor = Doctor::factory()->create();
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        Appointment::factory()->count(2)->create(['doctor_id' => $doctor->id, 'patient_id' => $patient->id]);

        $result = $this->appointmentService->getAppointmentsByPatient($patient->id);

        $this->assertTrue($result['success']);
        $this->assertCount(2, $result['appointments']);
    }

    public function test_get_appointments_by_patient_empty()
    {
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);

        $result = $this->appointmentService->getAppointmentsByPatient($patient->id);

        $this->assertTrue($result['success']);
        $this->assertCount(0, $result['appointments']);
    }

    public function test_confirm_appointment_by_doctor_success()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'status' => 'pending'
        ]);

        $result = $this->appointmentService->confirmAppointmentByDoctor($appointment->id, $doctor->id);

        $this->assertTrue($result['success']);
        $this->assertEquals('confirmed', $result['confirmed_appointment']->status);
    }

    public function test_confirm_appointment_by_doctor_unauthorized()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'status' => 'pending'
        ]);

        $result = $this->appointmentService->confirmAppointmentByDoctor($appointment->id, 9999);

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized or appointment not found', $result['message']);
    }

    public function test_complete_appointment_success()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'status' => 'confirmed'
        ]);

        $result = $this->appointmentService->completeAppointment($appointment->id, $doctor->id);

        $this->assertTrue($result['success']);
        $this->assertEquals('completed', $result['confirmed_appointment']->status);
    }

    public function test_complete_appointment_unauthorized()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'status' => 'confirmed'
        ]);

        $result = $this->appointmentService->completeAppointment($appointment->id, 9999);

        $this->assertFalse($result['success']);
        $this->assertEquals('Unauthorized or appointment not found', $result['message']);
    }

    public function test_new_appointment_with_specialist_doctor()
    {
        $cardiologist = Doctor::factory()->specialist('Cardiologist')->create();
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        $time = now()->addDay()->format('Y-m-d H:i:s');

        $result = $this->appointmentService->newAppointment($patient->id, $cardiologist->id, $time, 'Heart checkup');

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $cardiologist->id,
            'patient_id' => $patient->id,
        ]);
    }

    public function test_new_appointment_with_patient_with_allergies()
    {
        $doctor = Doctor::factory()->create();
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->withAllergies('Penicillin')->create(['user_id' => $userPatient->id]);
        $time = now()->addDay()->format('Y-m-d H:i:s');

        $result = $this->appointmentService->newAppointment($patient->id, $doctor->id, $time, 'Please note my allergies');

        $this->assertTrue($result['success']);
        $this->assertEquals('Penicillin', $patient->fresh()->allergies);
    }

    public function test_emergency_doctor_appointments()
    {
        $emergencyDoctor = Doctor::factory()->emergency()->create();
        $userPatient = User::factory()->patient()->create();
        $patient = Patient::factory()->create(['user_id' => $userPatient->id]);
        $time = now()->addHour()->format('Y-m-d H:i:s');

        $result = $this->appointmentService->newAppointment($patient->id, $emergencyDoctor->id, $time, 'Emergency case');

        $this->assertTrue($result['success']);
        $this->assertEquals('Emergency Medicine', $emergencyDoctor->specialist);
    }
}

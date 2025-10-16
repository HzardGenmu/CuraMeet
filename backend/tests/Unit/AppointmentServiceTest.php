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
        $patient = Patient::factory()->create();
        $time = now()->addDay()->format('Y-m-d H:i:s');

        $result = $this->appointmentService->newAppointment($patient->id, $doctor->id, $time, 'Catatan pasien');

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'time_appointment' => $time,
            'patient_note' => 'Catatan pasien'
        ]);
    }

    public function test_new_appointment_doctor_not_available()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        $time = now()->addDay()->format('Y-m-d H:i:s');
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
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);

        Auth::shouldReceive('id')->andReturn($user->id);

        $result = $this->appointmentService->cancelAppointment($appointment->id, 'Alasan batal');

        $this->assertTrue($result['success']);
        $this->assertEquals('cancelled', $result['cancelled_appointment']->status);
        $this->assertEquals('patient', $result['cancelled_appointment']->cancelled_by);
    }

    public function test_cancel_appointment_unauthorized()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);

        Auth::shouldReceive('id')->andReturn(9999); // user_id salah

        $result = $this->appointmentService->cancelAppointment($appointment->id, 'Alasan batal');

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
    }

    public function test_change_schedule_by_doctor_unauthorized()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);
        $newTime = now()->addDays(2)->format('Y-m-d H:i:s');

        $result = $this->appointmentService->changeScheduleByDoctor($appointment->id, $newTime, 9999);

        $this->assertFalse($result['success']);
    }

    public function test_cancel_appointment_by_doctor_success()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);

        $result = $this->appointmentService->cancelAppointmentByDoctor($appointment->id, 'Alasan dokter', $doctor->id);

        $this->assertTrue($result['success']);
        $this->assertEquals('doctor', $result['cancelled_appointment']->cancelled_by);
        $this->assertEquals('cancelled', $result['cancelled_appointment']->status);
    }

    public function test_get_appointments_by_doctor()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        Appointment::factory()->create(['doctor_id' => $doctor->id, 'patient_id' => $patient->id]);

        $result = $this->appointmentService->getAppointmentsByDoctor($doctor->id);

        $this->assertTrue($result['success']);
        $this->assertCount(1, $result['appointments']);
    }

    public function test_change_appointment_by_patient_success()
    {
        $patient = Patient::factory()->create();
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);
        $newTime = now()->addDays(3)->format('Y-m-d H:i:s');

        $result = $this->appointmentService->changeAppointmentByPatient($appointment->id, $newTime, $patient->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($newTime, $result['updated_appointment']->time_appointment);
    }

    public function test_get_appointments_by_patient()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        Appointment::factory()->create(['doctor_id' => $doctor->id, 'patient_id' => $patient->id]);

        $result = $this->appointmentService->getAppointmentsByPatient($patient->id);

        $this->assertTrue($result['success']);
        $this->assertCount(1, $result['appointments']);
    }
}

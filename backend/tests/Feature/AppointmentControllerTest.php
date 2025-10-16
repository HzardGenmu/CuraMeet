<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_appointment_success()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        $response = $this->postJson('/api/appointments/new', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'patient_note' => 'Test note'
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
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
        $response = $this->postJson('/api/appointments/new', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_time' => $time,
            'patient_note' => 'Test note'
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    public function test_cancel_appointment_by_patient()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);
        $this->actingAs($user);
        $response = $this->postJson("/api/appointments/{$appointment->id}/cancel", [
            'reason' => 'Tidak jadi'
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_cancel_appointment_by_doctor()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);
        $response = $this->postJson('/api/appointments/cancel-by-doctor', [
            'appointment_id' => $appointment->id,
            'reason' => 'Dokter berhalangan',
            'doctor_id' => $doctor->id
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_get_appointments_by_doctor()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        Appointment::factory()->create(['doctor_id' => $doctor->id, 'patient_id' => $patient->id]);
        $response = $this->getJson('/api/appointments/doctor?doctor_id=' . $doctor->id);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_change_schedule_by_doctor()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);
        $newTime = now()->addDays(2)->format('Y-m-d H:i:s');
        $response = $this->postJson('/api/appointments/change-schedule/doctor', [
            'appointment_id' => $appointment->id,
            'doctor_id' => $doctor->id,
            'new_time' => $newTime
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['result']);
    }

    public function test_get_appointments_by_patient()
    {
        $doctor = Doctor::factory()->create();
        $patient = Patient::factory()->create();
        Appointment::factory()->create(['doctor_id' => $doctor->id, 'patient_id' => $patient->id]);
        $response = $this->getJson('/api/appointments/patient?patient_id=' . $patient->id);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_change_appointment_by_patient()
    {
        $patient = Patient::factory()->create();
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);
        $newTime = now()->addDays(3)->format('Y-m-d H:i:s');
        $response = $this->postJson('/api/appointments/change-schedule/patient', [
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'new_time' => $newTime
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['result']);
    }

    public function test_bulk_update_appointments()
    {
        $appointment = Appointment::factory()->create();
        $response = $this->postJson('/api/appointments/bulk-update', [
            'appointments' => [
                [
                    'id' => $appointment->id,
                    'status' => 'confirmed',
                    'new_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
                    'patient_note' => 'Updated by admin'
                ]
            ]
        ]);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}

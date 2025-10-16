<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $doctorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->doctorService = app(DoctorService::class);
    }

    public function test_get_doctor_by_user_id_returns_doctor()
    {
        $user = User::factory()->create();
        $doctor = Doctor::factory()->create(['user_id' => $user->id]);

        $result = $this->doctorService->getDoctorByUserId($user->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($doctor->id, $result['doctor']->id);
    }

    public function test_get_doctor_by_user_id_not_found()
    {
        $result = $this->doctorService->getDoctorByUserId(9999);

        $this->assertFalse($result['success']);
        $this->assertEquals('Doctor not found', $result['message']);
    }

    public function test_get_doctor_by_id_returns_doctor()
    {
        $doctor = Doctor::factory()->create();

        $result = $this->doctorService->getDoctorById($doctor->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($doctor->id, $result['doctor']->id);
    }

    public function test_get_doctor_by_id_not_found()
    {
        $result = $this->doctorService->getDoctorById(9999);

        $this->assertFalse($result['success']);
        $this->assertEquals('Doctor not found', $result['message']);
    }

    public function test_get_doctors_by_name_returns_matching_doctors()
    {
        Doctor::factory()->create(['full_name' => 'Dr. John Doe']);
        Doctor::factory()->create(['full_name' => 'Dr. Jane Smith']);

        $result = $this->doctorService->getDoctorsByName('Jane');

        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['count']);
        $this->assertEquals('Dr. Jane Smith', $result['doctors'][0]->full_name);
    }

    public function test_list_doctors_returns_all_doctors()
    {
        Doctor::factory()->count(3)->create();

        $result = $this->doctorService->listDoctors();

        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['count']);
        $this->assertIsArray($result['doctors']->toArray());
    }
}

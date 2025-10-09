<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
        ]);

        // Create patients with their user accounts
        $patients = Patient::factory(50)->create();

        // Create doctors with their user accounts
        $doctors = Doctor::factory(15)->create();

        // Create appointments
        Appointment::factory(100)
            ->recycle($patients)
            ->recycle($doctors)
            ->create();

        // Create some specific appointment states
        Appointment::factory(20)->pending()
            ->recycle($patients)
            ->recycle($doctors)
            ->create();

        Appointment::factory(15)->today()
            ->recycle($patients)
            ->recycle($doctors)
            ->create();

        // Create medical records
        MedicalRecord::factory(200)
            ->recycle($patients)
            ->recycle($doctors)
            ->create();

        // Create some specific medical records
        MedicalRecord::factory(30)->chronicDisease()
            ->recycle($patients)
            ->recycle($doctors)
            ->create();
    }
}

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
        // 1️⃣ Admin
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
        ]);

        // 2️⃣ Users role patient + Patient model
        $patients = User::factory()
            ->count(50)
            ->patient()
            ->has(Patient::factory()) // otomatis isi user_id
            ->create();

        // 3️⃣ Users role doctor + Doctor model
        $doctors = User::factory()
            ->count(15)
            ->doctor()
            ->has(Doctor::factory()) // otomatis isi user_id
            ->create();

        // 4️⃣ Appointments
        Appointment::factory(100)
            ->recycle($patients->pluck('patient'))
            ->recycle($doctors->pluck('doctor'))
            ->create();

        Appointment::factory(20)->pending()
            ->recycle($patients->pluck('patient'))
            ->recycle($doctors->pluck('doctor'))
            ->create();

        Appointment::factory(15)->today()
            ->recycle($patients->pluck('patient'))
            ->recycle($doctors->pluck('doctor'))
            ->create();

        // 5️⃣ Medical Records
        MedicalRecord::factory(200)
            ->recycle($patients->pluck('patient'))
            ->recycle($doctors->pluck('doctor'))
            ->create();

        MedicalRecord::factory(30)->chronicDisease()
            ->recycle($patients->pluck('patient'))
            ->recycle($doctors->pluck('doctor'))
            ->create();
    }


}

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

        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
        ]);

        $patients = User::factory()
            ->count(50)
            ->patient()
            ->has(Patient::factory()->state(function (array $attributes, User $user) {
                return ['full_name' => $user->name];
            }))
            ->create();

        $doctors = User::factory()
            ->count(15)
            ->doctor()
            ->has(Doctor::factory()->state(function (array $attributes, User $user) {
                return ['full_name' => $user->name];
            }))
            ->create();

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

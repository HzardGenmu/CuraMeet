<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $user = User::factory()->doctor()->create();

        $specialists = [
            'Cardiologist',
            'Dermatologist',
            'Neurologist',
            'Pediatrician',
            'Orthopedic Surgeon',
            'Psychiatrist',
            'General Practitioner',
            'Gynecologist',
            'Urologist',
            'Oncologist',
            'Endocrinologist',
            'Ophthalmologist',
            'Emergency Medicine',
        ];

        $polyclinics = [
            'Internal Medicine',
            'Surgery',
            'Pediatrics',
            'Obstetrics & Gynecology',
            'Neurology',
            'Cardiology',
            'Dermatology',
            'Psychiatry',
            'Orthopedics',
            'Emergency Medicine',
        ];

        $availableTimes = [
            'Monday-Friday: 08:00-16:00',
            'Monday-Wednesday: 09:00-17:00',
            'Tuesday-Thursday: 10:00-18:00',
            'Monday-Friday: 07:00-15:00',
            'Weekend: 08:00-12:00',
            'Monday-Friday: 13:00-21:00',
            'Daily: 24/7 Emergency',
        ];
        return [
            'full_name' => fake()->name(), // ✅ Generate langsung, jangan pakai closure
            'str_number' => 'STR-' . fake()->unique()->numerify('########'),
            'specialist' => fake()->randomElement($specialists),
            'polyclinic' => fake()->randomElement($polyclinics),
            'available_time' => fake()->randomElement($availableTimes),
        ];
    }

    /**
     * Create a doctor with specific specialist.
     */
    public function specialist(string $specialist): static
    {
        return $this->state(fn(array $attributes) => [
            'specialist' => $specialist,
        ]);
    }

    /**
     * Create a general practitioner.
     */
    public function generalPractitioner(): static
    {
        return $this->state(fn(array $attributes) => [
            'specialist' => 'General Practitioner',
            'polyclinic' => 'Internal Medicine',
        ]);
    }

    /**
     * Create an emergency doctor.
     */
    public function emergency(): static
    {
        return $this->state(fn(array $attributes) => [
            'specialist' => 'Emergency Medicine',
            'polyclinic' => 'Emergency Medicine',
            'available_time' => 'Daily: 24/7 Emergency',
        ]);
    }
}

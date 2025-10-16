<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        // Generate appointment time (working day, working hour)
        $workingDays = [1, 2, 3, 4, 5]; // Monday to Friday
        $workingHours = [8, 9, 10, 11, 13, 14, 15, 16, 17];

        $appointmentTime = Carbon::now()->addHours(rand(1, 72));
        while (!in_array($appointmentTime->format('N'), $workingDays)) {
            $appointmentTime->addDay();
        }
        $appointmentTime->setTime(
            fake()->randomElement($workingHours),
            fake()->randomElement([0, 30]),
            0
        );

        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'time_appointment' => $appointmentTime,
            'status' => fake()->randomElement($statuses),
            'doctor_note' => fake()->sentence(),
            'patient_note' => fake()->sentence(),
            'cancellation_reason' => fake()->sentence(),
            'cancelled_by' => fake()->randomElement(['doctor', 'patient']),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'time_appointment' => fake()->dateTimeBetween('+1 day', '+2 months'),
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'confirmed',
            'time_appointment' => fake()->dateTimeBetween('+1 hour', '+2 months'),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
            'time_appointment' => fake()->dateTimeBetween('-1 month', '-1 hour'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'cancelled',
            'cancellation_reason' => fake()->sentence(),
            'cancelled_by' => fake()->randomElement(['patient', 'doctor', 'admin']),
        ]);
    }

    public function today(): static
    {
        $today = Carbon::today();
        $appointmentTime = $today->copy()->setTime(
            fake()->randomElement([8, 9, 10, 11, 13, 14, 15, 16, 17]),
            fake()->randomElement([0, 30]),
            0
        );

        return $this->state(fn(array $attributes) => [
            'time_appointment' => $appointmentTime,
            'status' => fake()->randomElement(['pending', 'confirmed']),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn(array $attributes) => [
            'time_appointment' => fake()->dateTimeBetween('+1 hour', '+1 month'),
            'status' => fake()->randomElement(['pending', 'confirmed']),
        ]);
    }
}

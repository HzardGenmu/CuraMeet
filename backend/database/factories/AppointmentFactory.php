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

        // Generate appointment time between 1 month ago and 2 months in the future
        $appointmentTime = fake()->dateTimeBetween('-1 month', '+2 months');

        // Working hours: 8 AM to 6 PM, weekdays only
        $workingDays = [1, 2, 3, 4, 5]; // Monday to Friday
        $workingHours = [8, 9, 10, 11, 13, 14, 15, 16, 17]; // Skip 12 PM (lunch)

        $appointmentTime->setTime(
            fake()->randomElement($workingHours),
            fake()->randomElement([0, 30]), // 0 or 30 minutes
            0
        );


        $appointmentTime = Carbon::now()->addHours(rand(1, 72));

        // Adjust to working day if it falls on weekend
        while (!in_array($appointmentTime->format('N'), $workingDays)) {
            $appointmentTime->addDay();
        }
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'time_appointment' => $appointmentTime,
            'status' => fake()->randomElement($statuses),
        ];
    }

    /**
     * Create a pending appointment.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'time_appointment' => fake()->dateTimeBetween('+1 day', '+2 months'),
        ]);
    }

    /**
     * Create a confirmed appointment.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'time_appointment' => fake()->dateTimeBetween('+1 hour', '+2 months'),
        ]);
    }

    /**
     * Create a completed appointment.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'time_appointment' => fake()->dateTimeBetween('-1 month', '-1 hour'),
        ]);
    }

    /**
     * Create a cancelled appointment.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Create an appointment for today.
     */
    public function today(): static
    {
        $today = Carbon::today();
        $appointmentTime = $today->copy()->setTime(
            fake()->randomElement([8, 9, 10, 11, 13, 14, 15, 16, 17]),
            fake()->randomElement([0, 30]),
            0
        );

        return $this->state(fn (array $attributes) => [
            'time_appointment' => $appointmentTime,
            'status' => fake()->randomElement(['pending', 'confirmed']),
        ]);
    }

    /**
     * Create an upcoming appointment.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_appointment' => fake()->dateTimeBetween('+1 hour', '+1 month'),
            'status' => fake()->randomElement(['pending', 'confirmed']),
        ]);
    }
}

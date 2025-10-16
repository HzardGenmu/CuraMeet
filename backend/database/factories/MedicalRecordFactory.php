<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $diseases = [
            'Hypertension',
            'Type 2 Diabetes',
            'Common Cold',
            'Migraine',
            'Gastritis',
            'Anxiety Disorder',
            'Asthma',
            'Skin Allergies',
            'Lower Back Pain',
            'Insomnia',
            'High Cholesterol',
            'Bronchitis',
            'Urinary Tract Infection',
            'Depression',
            'Arthritis',
            'Acid Reflux',
            'Sinusitis',
        ];

        $fileTypes = ['pdf', 'doc', 'docx', 'jpg', 'png'];
        $hasFile = fake()->boolean(70); // 70% chance of having a file

        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'disease_name' => fake()->randomElement($diseases),
            'path_file' => $hasFile ? 'medical_records/' . fake()->uuid() . '.' . fake()->randomElement($fileTypes) : null,
            'catatan_dokter' => fake()->sentence(),
        ];
    }

    /**
     * Create a medical record with specific disease.
     */
    public function withDisease(string $disease): static
    {
        return $this->state(fn(array $attributes) => [
            'disease_name' => $disease,
        ]);
    }

    /**
     * Create a medical record with file.
     */
    public function withFile(string $extension = 'pdf'): static
    {
        return $this->state(fn(array $attributes) => [
            'path_file' => 'medical_records/' . fake()->uuid() . '.' . $extension,
        ]);
    }

    /**
     * Create a medical record without file.
     */
    public function withoutFile(): static
    {
        return $this->state(fn(array $attributes) => [
            'path_file' => null,
        ]);
    }

    /**
     * Create a chronic disease record.
     */
    public function chronicDisease(): static
    {
        $chronicDiseases = [
            'Type 2 Diabetes',
            'Hypertension',
            'Asthma',
            'Arthritis',
            'High Cholesterol',
            'Chronic Kidney Disease',
        ];

        return $this->state(fn(array $attributes) => [
            'disease_name' => fake()->randomElement($chronicDiseases),
        ]);
    }

    /**
     * Create an acute condition record.
     */
    public function acuteCondition(): static
    {
        $acuteConditions = [
            'Common Cold',
            'Gastritis',
            'Urinary Tract Infection',
            'Bronchitis',
            'Sinusitis',
            'Food Poisoning',
        ];

        return $this->state(fn(array $attributes) => [
            'disease_name' => fake()->randomElement($acuteConditions),
        ]);
    }
}

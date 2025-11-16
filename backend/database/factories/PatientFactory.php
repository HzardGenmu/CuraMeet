<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        // ✅ JANGAN create User di sini, biarkan null
        // User akan di-assign dari relasi has() di seeder

        $pictureUrl = fake()->optional()->imageUrl(200, 200, 'people');
        $filename = $pictureUrl ? basename($pictureUrl) : null;
        $picturePath = $filename ? 'storage/patients/' . $filename : null;

        return [
            // 'user_id' akan otomatis diisi oleh Laravel dari relasi
            'full_name' => fake()->name(), // ✅ Generate langsung, jangan pakai closure
            'NIK' => fake()->unique()->numerify('################'),
            'picture' => $picturePath,
            'allergies' => fake()->optional()->randomElement([
                'Peanuts, Shellfish',
                'Penicillin',
                'Dust mites, Pollen',
                'Latex',
                'None known',
                'Aspirin, Ibuprofen',
                'Eggs, Milk',
            ]),
            'disease_histories' => fake()->optional()->randomElement([
                'Hypertension (2020)',
                'Diabetes Type 2 (2019)',
                'Asthma since childhood',
                'Previous surgery: Appendectomy (2018)',
                'Migraine headaches',
                'None',
                'Heart disease (2021), High cholesterol',
            ]),
        ];
    }

    public function withAllergies(string $allergies): static
    {
        return $this->state(fn(array $attributes) => [
            'allergies' => $allergies,
        ]);
    }
}

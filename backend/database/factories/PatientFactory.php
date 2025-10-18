<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->patient()->create();
        $pictureUrl = fake()->optional()->imageUrl(200, 200, 'people');
        $filename = $pictureUrl ? basename($pictureUrl) : null;
        $picturePath = $filename ? 'storage/patients/' . $filename : null;

        return [
            'user_id' => $user->id,
            'full_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->name;
            },
            'NIK' => fake()->unique()->numerify('################'),
            'picture' => $picturePath,
            'allergies' => fake()->optional()->randomElement([
                'Peanuts, Shellfish',
                'Penicilliclsn',
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

    /**
     * State for patient with specific allergies
     */
    public function withAllergies(string $allergies): static
    {
        return $this->state(fn(array $attributes) => [
            'allergies' => $allergies,
        ]);
    }
}

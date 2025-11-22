<?php

namespace Database\Factories;

use App\Models\Atelier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AtelierFactory extends Factory
{
    protected $model = Atelier::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'date' => Carbon::now(),
            'description' => $this->faker->sentence(),
            'duree' => $this->faker->numberBetween(1, 8),
            'prix' => $this->faker->randomFloat(2, 10, 100),
            'employe_id' => null,
            'intervenant' => [
                'nom' => $this->faker->lastName(),
                'prenom' => $this->faker->firstName(),
                'email' => $this->faker->unique()->safeEmail(),
                'telephone' => $this->faker->phoneNumber(),
            ],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Adresse;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdresseFactory extends Factory
{
    protected $model = Adresse::class;

    public function definition(): array
    {
        return [
            'rue' => $this->faker->streetName(),
            'numero' => $this->faker->numberBetween(1, 49),
            'ville' => $this->faker->city(),
            'code_postal' => $this->faker->postcode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Adresse;
use Illuminate\Support\Carbon;

class AdresseFactory
{
    protected $model = Adresse::class;
    public function definition(): array
    {
        return [
            'rue' => $this->faker->word(),
            'numero' => $this->faker->numberBetween(1, 49),
            'ville' => $this->faker->word(),
            'code_postal' => $this->faker->postcode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SalleFactory extends Factory
{
    protected $model = Salle::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->name(),
            'capacite' => $this->faker->numberBetween(1,100),
            'category' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

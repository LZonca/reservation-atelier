<?php

namespace Database\Factories;

use App\Models\Boutique;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BoutiqueFactory extends Factory
{
    protected $model = Boutique::class;
    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'adresse' => [
                'rue'=>$this->faker->word(),
                'numero'=> $this->faker->numberBetween(1, 49),
                'ville'=>$this->faker->word(),
                'code_postal'=>$this->faker->postcode(),
            ],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

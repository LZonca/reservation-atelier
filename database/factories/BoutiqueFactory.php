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
            'nom' => $this->faker->company(),
            'adresse' => [
                'rue'=>$this->faker->streetName(),
                'numero'=> $this->faker->numberBetween(1, 49),
                'ville'=>$this->faker->city(),
                'code_postal'=>$this->faker->postcode(),
            ],
            'infoContact' => [
                'email'=>$this->faker->unique()->safeEmail(),
                'telephone'=>$this->faker->phoneNumber(),

                // Les réseaux sociaux sont optionnels : certaines boutiques ne les auront pas
                'website' => $this->faker->optional(0.7)->url(),
                'youtube' => $this->faker->optional(0.4)->url(),
                'instagram' => $this->faker->optional(0.7)->url(),
                'facebook' => $this->faker->optional(0.7)->url(),
                'twitter' => $this->faker->optional(0.4)->url(),
                'pinterest' => $this->faker->optional(0.6)->url(),
                'bluesky' => $this->faker->optional(0.3)->url
            ],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

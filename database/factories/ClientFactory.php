<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $nom = $this->faker->lastName();
        $prenom = $this->faker->firstName();
        return [
            'nom' => $nom,
            'prenom' => $prenom,
            // email is THE nom.prenom@gmail.com
            'email' => strtolower($prenom) . '.' . strtolower($nom) . rand(0, 999999) . '@gmail.com',
            'phone' => $this->faker->phoneNumber(),
            'panier' => [
                'ateliers' => [],
            ],
            'credit_fidelite' => $this->faker->numberBetween(0, 20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

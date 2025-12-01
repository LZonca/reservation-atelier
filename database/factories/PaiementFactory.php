<?php

namespace Database\Factories;

use App\Models\Paiement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'numCarte' => $this->faker->creditCardNumber(),
            'montant' => $this->faker->randomFloat(2, 10, 500),
            'statut' => $this->faker->randomElement(['en_attente', 'confirmé', 'annulé', 'remboursé']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

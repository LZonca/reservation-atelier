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
        $salleTypes = ['Studio', 'Salle', 'Espace'];
        $salleNames = ['des Arts', 'Créative', 'Lumière', 'Harmonie', 'Renaissance', 'Inspiration', 'Émeraude', 'Azur'];

        return [
            'nom' => $this->faker->randomElement($salleTypes) . ' ' . $this->faker->randomElement($salleNames),
            'capacite' => $this->faker->numberBetween(5, 50),
            'categorie' => $this->faker->randomElement([
                'Peinture',
                'Sculpture',
                'Danse',
                'Musique',
                'Arts plastiques',
                'Multimédia',
                'Théâtre',
                'Artisanat',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

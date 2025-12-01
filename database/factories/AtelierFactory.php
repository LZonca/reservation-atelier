<?php

// ============================================
// Database/Factories/AtelierFactory.php
// ============================================

namespace Database\Factories;

use App\Models\Atelier;
use App\Models\User;
use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use MongoDB\BSON\ObjectId;

class AtelierFactory extends Factory
{
    protected $model = Atelier::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->words(3, true),
            'date' => $this->faker->dateTimeBetween('now', '+2 week'),
            'description' => $this->faker->sentence(10),
            'duree' => $this->faker->numberBetween(1, 4),
            'prix' => $this->faker->randomFloat(2, 10, 100),
            'categorie' => $this->faker->randomElement([
                'Peinture',
                'Potterie',
                'Arts du cirque',
                'Crochet',
                'Sculpture',
                'Photographie',
                'Danse',
                'Théâtre',
                'Musique',
                'Cuisine',
                'Écriture créative',
                'Jardinage',
                'DIY',
                'Bijouterie',
                'Tricot',
                'Calligraphie',
                'Décoration intérieure',
                'Yoga',
                'Méditation',
                'Langues étrangères',
            ]),
            'intervenant' => [
                'nom' => $this->faker->lastName(),
                'prenom' => $this->faker->firstName(),
                'email' => $this->faker->unique()->safeEmail(),
                'telephone' => $this->faker->phoneNumber(),
            ],
            'vip' => $this->faker->boolean(30), // 30% de chances d'être VIP
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Configure le factory pour assigner automatiquement un employé
     */
    public function withEmploye(?User $employe = null): static
    {
        return $this->state(function (array $attributes) use ($employe) {
            $user = $employe ?? User::inRandomOrder()->first();

            return [
                'employe_id' => $user ? new ObjectId((string) $user->_id) : null,
            ];
        });
    }

    /**
     * Configure le factory pour assigner automatiquement une salle
     */
    public function withSalle(?Salle $salle = null): static
    {
        return $this->state(function (array $attributes) use ($salle) {
            $room = $salle ?? Salle::inRandomOrder()->first();

            return [
                'salle_id' => $room ? new ObjectId((string) $room->_id) : null,
            ];
        });
    }

    /**
     * Atelier VIP
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'vip' => true,
            'prix' => $this->faker->randomFloat(2, 100, 500),
        ]);
    }

    /**
     * Atelier gratuit
     */
    public function gratuit(): static
    {
        return $this->state(fn (array $attributes) => [
            'prix' => 0,
            'vip' => false,
        ]);
    }
}

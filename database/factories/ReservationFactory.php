<?php

// ============================================
// Database/Factories/ReservationFactory.php
// ============================================

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Atelier;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use MongoDB\BSON\ObjectId;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'prix' => $this->faker->randomFloat(2, 20, 500),
            'nbPersonne' => $this->faker->numberBetween(1, 10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Configure le factory pour assigner un atelier
     */
    public function forAtelier(?Atelier $atelier = null): static
    {
        return $this->state(function (array $attributes) use ($atelier) {
            $workshop = $atelier ?? Atelier::inRandomOrder()->first();

            if (!$workshop) {
                return [];
            }

            $nbPersonnes = $attributes['nbPersonne'] ?? $this->faker->numberBetween(1, 10);

            return [
                'atelier_id' => new ObjectId((string)$workshop->_id),
                'prix' => $workshop->prix * $nbPersonnes,
            ];
        });
    }

    /**
     * Configure le factory pour assigner un client
     */
    public function forClient(?Client $client = null): static
    {
        return $this->state(function (array $attributes) use ($client) {
            $customer = $client ?? Client::inRandomOrder()->first();

            return [
                'client_id' => $customer ? new ObjectId((string)$customer->_id) : null,
            ];
        });
    }

    /**
     * Réservation pour un groupe
     */
    public function groupe(): static
    {
        return $this->state(fn(array $attributes) => [
            'nbPersonne' => $this->faker->numberBetween(5, 10),
        ]);
    }

    /**
     * Réservation individuelle
     */
    public function individuelle(): static
    {
        return $this->state(fn(array $attributes) => [
            'nbPersonne' => 1,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Panier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PanierFactory extends Factory
{
    protected $model = Panier::class;

    public function definition(): array
    {
        return [
            'expires_at' => now()->addMinutes(20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

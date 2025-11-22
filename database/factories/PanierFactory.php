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
            'expires_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

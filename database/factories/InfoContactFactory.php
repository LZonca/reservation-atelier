<?php

namespace Database\Factories;

use App\Models\InfoContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfoContactFactory extends Factory
{
    protected $model = InfoContact::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->unique()->phoneNumber(),
            'website' => $this->faker->optional(0.7)->url(),
            'youtube' => $this->faker->optional(0.4)->url(),
            'instagram' => $this->faker->optional(0.7)->url(),
            'facebook' => $this->faker->optional(0.7)->url(),
            'twitter' => $this->faker->optional(0.4)->url(),
            'pinterest' => $this->faker->optional(0.6)->url(),
            'bluesky' => $this->faker->optional(0.3)->url(),
        ];
    }
}


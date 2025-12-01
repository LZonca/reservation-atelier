<?php

namespace Database\Factories;

use App\Models\InfoContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfoContactFactory extends Factory
{
    protected $model = InfoContact::class;

    public function definition(): array
    {
        $username = $this->faker->userName();

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'website' => $this->faker->optional(0.7)->domainName(),
            'youtube' => $this->faker->optional(0.4)->passthrough('https://youtube.com/@' . $username),
            'instagram' => $this->faker->optional(0.7)->passthrough('https://instagram.com/' . $username),
            'facebook' => $this->faker->optional(0.7)->passthrough('https://facebook.com/' . $username),
            'twitter' => $this->faker->optional(0.4)->passthrough('https://twitter.com/' . $username),
            'pinterest' => $this->faker->optional(0.6)->passthrough('https://pinterest.com/' . $username),
            'bluesky' => $this->faker->optional(0.3)->passthrough('https://bsky.app/profile/' . $username),
        ];
    }
}


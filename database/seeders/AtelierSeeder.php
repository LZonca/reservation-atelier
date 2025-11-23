<?php

namespace Database\Seeders;

use App\Models\Atelier;
use App\Models\Boutique;
use App\Models\User;
use Illuminate\Database\Seeder;

class AtelierSeeder extends Seeder
{
    public function run(): void
    {

        $users = User::all();
        $boutiques = Boutique::all();

        if ($users->isEmpty() || $boutiques->isEmpty()) {
            $this->command->error('   ✗ Erreur : Des utilisateurs et salles doivent exister');
            return;
        }

        foreach (range(1, 25) as $index) {
            $atelier = Atelier::factory()->create();

            $atelier->employe()->associate($users->random());
            $atelier->salle()->associate($boutiques->random());
            $atelier->save();
        }
    }
}

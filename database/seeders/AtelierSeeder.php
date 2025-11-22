<?php

namespace Database\Seeders;

use App\Models\Atelier;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Seeder;

class AtelierSeeder extends Seeder
{
    public function run(): void
    {

        $users = User::all();
        $salles = Salle::all();

        if ($users->isEmpty() || $salles->isEmpty()) {
            $this->command->error('   ✗ Erreur : Des utilisateurs et salles doivent exister');
            return;
        }

        foreach (range(1, 25) as $index) {
            $atelier = Atelier::factory()->create();

            // Associer un employé et une salle
            $atelier->employe()->associate($users->random());
            $atelier->salle()->associate($salles->random());
            $atelier->save();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Boutique;
use App\Models\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{

    public function run(): void
    {
        $boutique = Boutique::all();
        if ($boutique->isEmpty() ) {
            $this->command->error('   ✗ Erreur : Des boutiques doivent exister');
            return;
        }

        // Salles avec des noms spécifiques
        $salles = [
            ['nom' => 'Salle A', 'capacite' => 20, 'categorie' => 'Potterie','boutique'=>$boutique->random()],
            ['nom' => 'Salle B', 'capacite' => 15, 'categorie' => 'Peinture','boutique'=>$boutique->random()],
            ['nom' => 'Salle C', 'capacite' => 30, 'categorie' => 'Sculpture','boutique'=>$boutique->random()],
            ['nom' => 'Salle VIP', 'capacite' => 10, 'categorie' => 'VIP','boutique'=>$boutique->random()],
            ['nom' => 'Grande Salle', 'capacite' => 50, 'categorie' => 'Événements','boutique'=>$boutique->random()],
        ];

        foreach ($salles as $salle) {
            Salle::factory()->create($salle);
        }

        // Salles aléatoires supplémentaires
        Salle::factory(5)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Atelier;
use Illuminate\Database\Seeder;
use MongoDB\BSON\ObjectId;

class PanierSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🛒 Création des paniers pour les clients...');

        // Récupérer tous les clients et ateliers
        $clients = Client::all();
        $ateliers = Atelier::all();

        if ($clients->isEmpty() || $ateliers->isEmpty()) {
            $this->command->warn('   ⚠ Pas de clients ou d\'ateliers trouvés. Création de paniers impossible.');
            return;
        }

        $totalPaniersRemplis = 0;

        // Remplir aléatoirement les paniers de 30% des clients
        $clientsAvecPanier = $clients->random(max(1, (int)($clients->count() * 0.3)));

        foreach ($clientsAvecPanier as $client) {
            $panier = [
                'ateliers' => [],
                'expires_at' => now()->addMinutes(rand(10, 30))
            ];

            // Ajouter entre 1 et 3 ateliers dans le panier
            $nbAteliersInPanier = rand(1, 3);
            $ateliersChoisis = $ateliers->random(min($nbAteliersInPanier, $ateliers->count()));

            foreach ($ateliersChoisis as $atelier) {
                $panier['ateliers'][] = [
                    'id' => new ObjectId((string) $atelier->_id),
                    'quantity' => rand(1, 4),
                    'nom' => $atelier->nom,
                    'prix' => $atelier->prix,
                ];
            }

            $client->panier = $panier;
            $client->save();

            $totalPaniersRemplis++;
        }

        $this->command->info("   ✓ {$totalPaniersRemplis} paniers remplis sur {$clients->count()} clients");
    }
}


<?php

namespace Database\Seeders;

use App\Models\Boutique;
use App\Models\Salle;
use Illuminate\Database\Seeder;
use MongoDB\BSON\ObjectId;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏢 Création des salles...');

        // Récupérer les boutiques
        $boutiques = Boutique::all();

        if ($boutiques->isEmpty()) {
            $this->command->error('   ✗ Erreur : Aucune boutique trouvée. Exécutez BoutiqueSeeder d\'abord.');
            return;
        }

        $this->command->info("   → {$boutiques->count()} boutiques disponibles");

        // Salles avec des noms spécifiques
        $salles = [
            [
                'nom' => 'Salle A',
                'capacite' => 20,
                'categorie' => 'Poterie',
                'boutique_id' => new ObjectId((string) $boutiques->random()->_id)
            ],
            [
                'nom' => 'Salle B',
                'capacite' => 15,
                'categorie' => 'Peinture',
                'boutique_id' => new ObjectId((string) $boutiques->random()->_id)
            ],
            [
                'nom' => 'Salle C',
                'capacite' => 30,
                'categorie' => 'Sculpture',
                'boutique_id' => new ObjectId((string) $boutiques->random()->_id)
            ],
            [
                'nom' => 'Salle VIP',
                'capacite' => 10,
                'categorie' => 'VIP',
                'boutique_id' => new ObjectId((string) $boutiques->random()->_id)
            ],
            [
                'nom' => 'Grande Salle',
                'capacite' => 50,
                'categorie' => 'Événements',
                'boutique_id' => new ObjectId((string) $boutiques->random()->_id)
            ],
        ];

        $createdCount = 0;

        foreach ($salles as $salleData) {
            Salle::create($salleData);
            $createdCount++;
        }

        $this->command->info("   ✓ {$createdCount} salles spécifiques créées");

        // Salles aléatoires supplémentaires
        $randomCount = 20;
        foreach (range(1, $randomCount) as $i) {
            Salle::factory()->create([
                'boutique_id' => new ObjectId((string) $boutiques->random()->_id)
            ]);
        }

        $this->command->info("   ✓ {$randomCount} salles aléatoires créées");

        $totalSalles = Salle::count();
        $this->command->info("   → Total: {$totalSalles} salles");
    }
}

<?php

namespace Database\Seeders;

use App\Models\Boutique;
use App\Models\User;
use Illuminate\Database\Seeder;
use MongoDB\BSON\ObjectId;

class BoutiqueSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏪 Création des boutiques et employés...');

        // Créer 3 boutiques
        $boutiques = Boutique::factory(3)->create();

        $this->command->info("   ✓ {$boutiques->count()} boutiques créées");

        $totalEmployes = 0;

        // Pour chaque boutique, créer des employés
        foreach ($boutiques as $boutique) {
            // Nombre d'employés par boutique (entre 3 et 8)
            $nbEmployes = rand(3, 8);

            for ($i = 0; $i < $nbEmployes; $i++) {
                User::factory()->create([
                    'boutique_id' => new ObjectId((string) $boutique->_id),
                ]);
                $totalEmployes++;
            }

            $this->command->info("   → Boutique '{$boutique->nom}': {$nbEmployes} employés");
        }

        $this->command->info("   ✓ Total: {$totalEmployes} employés créés");
    }
}

<?php

// ============================================
// Database/Seeders/AtelierSeeder.php
// ============================================

namespace Database\Seeders;

use App\Models\Atelier;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Seeder;
use MongoDB\BSON\ObjectId;

class AtelierSeeder extends Seeder
{
    public function run(): void
    {

        // Vérifier que les dépendances existent
        $users = User::all();
        $salles = Salle::all();

        if ($users->isEmpty()) {
            $this->command->error('   ✗ Erreur : Aucun utilisateur trouvé. Exécutez UserSeeder d\'abord.');
            return;
        }

        if ($salles->isEmpty()) {
            $this->command->error('   ✗ Erreur : Aucune salle trouvée. Exécutez SalleSeeder d\'abord.');
            return;
        }

        $this->command->info("   → {$users->count()} utilisateurs disponibles");
        $this->command->info("   → {$salles->count()} salles disponibles");

        // Créer des ateliers avec différentes configurations
        $ateliers = [];

        // 15 ateliers normaux
        foreach (range(1, 15) as $index) {
            $atelier = Atelier::factory()->create([
                'employe_id' => new ObjectId((string) $users->random()->_id),
                'salle_id' => new ObjectId((string) $salles->random()->_id),
            ]);
            $ateliers[] = $atelier;
        }

        // 5 ateliers VIP
        foreach (range(1, 5) as $index) {
            $atelier = Atelier::factory()->vip()->create([
                'employe_id' => new ObjectId((string) $users->random()->_id),
                'salle_id' => new ObjectId((string) $salles->random()->_id),
            ]);
            $ateliers[] = $atelier;
        }

        // 5 ateliers gratuits
        foreach (range(1, 5) as $index) {
            $atelier = Atelier::factory()->gratuit()->create([
                'employe_id' => new ObjectId((string) $users->random()->_id),
                'salle_id' => new ObjectId((string) $salles->random()->_id),
            ]);
            $ateliers[] = $atelier;
        }

        $this->command->info("   ✓ " . count($ateliers) . " ateliers créés avec succès");

        // Statistiques
        $vipCount = collect($ateliers)->where('vip', true)->count();
        $gratuitCount = collect($ateliers)->where('prix', 0)->count();

        $this->command->info("   → {$vipCount} ateliers VIP");
        $this->command->info("   → {$gratuitCount} ateliers gratuits");
    }
}

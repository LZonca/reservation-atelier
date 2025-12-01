<?php

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
        $this->command->info('🎨 Création des ateliers...');

        $users = User::all();
        $salles = Salle::all();

        if ($users->isEmpty() || $salles->isEmpty()) {
            $this->command->error('   ✗ Erreur : Des utilisateurs et salles doivent exister');
            return;
        }

        $this->command->info("   → {$users->count()} utilisateurs disponibles");
        $this->command->info("   → {$salles->count()} salles disponibles");

        $ateliers = [];

        // 15 ateliers normaux
        foreach (range(1, 15) as $index) {
            $this->command->info("   → Création atelier normal {$index}/15");

            // Essayer de créer un atelier sans collision
            $atelier = $this->createAtelierWithoutCollision($users, $salles, false);
            if ($atelier) {
                $ateliers[] = $atelier;
                $this->command->info("     ✓ Atelier créé avec succès");
            } else {
                $this->command->warn("     ⚠ Impossible de créer l'atelier {$index} (pas de créneau disponible)");
            }
        }

        // 5 ateliers VIP
        foreach (range(1, 5) as $index) {
            $this->command->info("   → Création atelier VIP {$index}/5");

            // Essayer de créer un atelier VIP sans collision
            $atelier = $this->createAtelierWithoutCollision($users, $salles, true);
            if ($atelier) {
                $ateliers[] = $atelier;
                $this->command->info("     ✓ Atelier VIP créé avec succès");
            } else {
                $this->command->warn("     ⚠ Impossible de créer l'atelier VIP {$index} (pas de créneau disponible)");
            }
        }

        $totalAteliers = count($ateliers);
        $this->command->info("   ✓ {$totalAteliers} ateliers créés avec succès");

        // Statistiques
        $vipCount = collect($ateliers)->where('vip', true)->count();
        $gratuitCount = collect($ateliers)->where('prix', 0)->count();

        $this->command->info("   → {$vipCount} ateliers VIP");
        $this->command->info("   → {$gratuitCount} ateliers gratuits");
    }

    private function createAtelierWithoutCollision($users, $salles, $isVip)
    {
        // Nombre maximum de tentatives pour trouver un créneau disponible
        $maxTentatives = 50;

        for ($tentative = 0; $tentative < $maxTentatives; $tentative++) {
            // Sélectionner un utilisateur et une salle aléatoirement
            $user = $users->random();
            $salle = $salles->random();

            // Générer une date aléatoire dans le mois prochain
            $dateDebut = now()->addDays(rand(1, 30));

            // Générer une durée aléatoire (1 à 4 heures)
            $duree = rand(1, 4);

            // Vérifier si la salle est disponible pour ce créneau
            if ($salle->isDisponible($dateDebut, $duree)) {
                // La salle est disponible, créer l'atelier
                $factory = Atelier::factory();

                if ($isVip) {
                    $factory = $factory->vip();
                }

                return $factory->create([
                    'employe_id' => new ObjectId((string) $user->_id),
                    'salle_id' => new ObjectId((string) $salle->_id),
                    'date' => $dateDebut,
                    'duree' => $duree,
                ]);
            }
        }

        // Aucune salle disponible après toutes les tentatives
        return null;
    }
}

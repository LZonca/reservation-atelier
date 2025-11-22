<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏢 Création des salles...');

        // Salles avec des noms spécifiques
        $salles = [
            ['nom' => 'Salle A', 'capacite' => 20, 'categorie' => 'Potterie'],
            ['nom' => 'Salle B', 'capacite' => 15, 'categorie' => 'Peinture'],
            ['nom' => 'Salle C', 'capacite' => 30, 'categorie' => 'Sculpture'],
            ['nom' => 'Salle VIP', 'capacite' => 10, 'categorie' => 'VIP'],
            ['nom' => 'Grande Salle', 'capacite' => 50, 'categorie' => 'Événements'],
        ];

        foreach ($salles as $salle) {
            Salle::factory()->create($salle);
        }

        // Salles aléatoires supplémentaires
        Salle::factory(5)->create();

        $this->command->info("   ✓ {$this->getSalleCount()} salles créées");
    }

    private function getSalleCount(): int
    {
        return Salle::count();
    }
}

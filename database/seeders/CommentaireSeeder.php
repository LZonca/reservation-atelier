<?php

namespace Database\Seeders;

use App\Models\Commentaire;
use App\Models\Client;
use App\Models\Atelier;
use Illuminate\Database\Seeder;
use MongoDB\BSON\ObjectId;

class CommentaireSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('💬 Création des commentaires...');

        // Récupérer tous les clients et ateliers
        $clients = Client::all();
        $ateliers = Atelier::all();

        if ($clients->isEmpty() || $ateliers->isEmpty()) {
            $this->command->warn('   ⚠ Pas de clients ou d\'ateliers trouvés. Création de commentaires impossible.');
            return;
        }

        $totalCommentaires = 0;

        // Créer entre 20 et 50 commentaires aléatoires
        $nbCommentaires = rand(20, 50);

        for ($i = 0; $i < $nbCommentaires; $i++) {
            $client = $clients->random();
            $atelier = $ateliers->random();

            // Vérifier que le client a une réservation pour cet atelier (optionnel)
            // Pour simplifier, on crée le commentaire directement

            Commentaire::create([
                'commentaire' => fake()->paragraph(rand(2, 5)),
                'note' => fake()->numberBetween(1, 5),
                'client_id' => new ObjectId((string) $client->_id),
                'atelier_id' => new ObjectId((string) $atelier->_id),
                'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
                'updated_at' => now(),
            ]);

            $totalCommentaires++;
        }

        $this->command->info("   ✓ {$totalCommentaires} commentaires créés");
    }
}


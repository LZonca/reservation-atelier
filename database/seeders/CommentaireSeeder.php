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

        // Stratégie améliorée : chaque client a 30% de chance d'avoir des commentaires
        // S'il en a, il aura entre 1 et 3 commentaires
        foreach ($clients as $client) {
            // 30% de chance d'avoir des commentaires
            if (rand(1, 100) <= 30) {
                $nbCommentairesClient = rand(1, 3);

                for ($j = 0; $j < $nbCommentairesClient; $j++) {
                    $atelier = $ateliers->random();

                    // Commentaires variés et réalistes
                    $commentaires = [
                        "Atelier excellent ! J'ai beaucoup appris.",
                        "Très bonne expérience, je recommande vivement.",
                        "L'intervenant était très pédagogue et à l'écoute.",
                        "Atelier intéressant mais un peu court à mon goût.",
                        "Parfait pour découvrir ce domaine !",
                        "Je suis ravi d'avoir participé à cet atelier.",
                        "Contenu très riche, j'ai adoré !",
                        "Bon atelier mais j'attendais un peu plus de pratique.",
                        "Superbe moment, très enrichissant.",
                        "L'atelier était bien organisé et instructif.",
                        "Décevant, pas assez de contenu pour le prix.",
                        "Génial ! Exactement ce que je cherchais.",
                        "Atelier sympa mais trop de théorie.",
                        "Très pro, je reviendrai pour d'autres ateliers.",
                        "Bonne ambiance et apprentissage de qualité.",
                    ];

                    $notesPonderees = [1, 1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5];

                    Commentaire::create([
                        'commentaire' => $commentaires[array_rand($commentaires)],
                        'note' => $notesPonderees[array_rand($notesPonderees)],
                        'client_id' => new ObjectId((string) $client->_id),
                        'atelier_id' => new ObjectId((string) $atelier->_id),
                        'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                        'updated_at' => now(),
                    ]);

                    $totalCommentaires++;
                }
            }
        }

        $this->command->info("   ✓ {$totalCommentaires} commentaires créés (environ " . round($totalCommentaires / $clients->count() * 100, 1) . "% des clients ont commenté)");
    }
}


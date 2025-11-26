<?php

// ============================================
// Database/Seeders/ReservationSeeder.php
// ============================================

namespace Database\Seeders;

use App\Models\Atelier;
use App\Models\Client;
use Illuminate\Database\Seeder;
use MongoDB\BSON\ObjectId;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📅 Création des réservations et paiements...');

        // Vérifier que les dépendances existent
        $ateliers = Atelier::all();
        $clients = Client::all();

        if ($ateliers->isEmpty()) {
            $this->command->error('   ✗ Erreur : Aucun atelier trouvé. Exécutez AtelierSeeder d\'abord.');
            return;
        }

        if ($clients->isEmpty()) {
            $this->command->error('   ✗ Erreur : Aucun client trouvé. Exécutez ClientSeeder d\'abord.');
            return;
        }

        $this->command->info("   → {$ateliers->count()} ateliers disponibles");
        $this->command->info("   → {$clients->count()} clients disponibles");

        $totalReservations = 0;
        $totalPaiements = 0;
        $totalMontant = 0;

        foreach ($ateliers as $atelier) {
            $nbReservations = rand(2, 6);

            foreach (range(1, $nbReservations) as $i) {
                $client = $clients->random();
                $nbPersonnes = rand(1, 4);
                $prixTotal = $atelier->prix * $nbPersonnes;

                // Créer la réservation avec ObjectId explicites
                $reservation = $atelier->reservations()->create([
                    'prix' => $prixTotal,
                    'nbPersonne' => $nbPersonnes,
                    'client_id' => new ObjectId((string) $client->_id),
                    'atelier_id' => new ObjectId((string) $atelier->_id),
                ]);

                $totalReservations++;
                $totalMontant += $prixTotal;

                // Créer les paiements
                $nbPaiements = rand(1, 3);
                $montantRestant = $prixTotal;

                for ($j = 0; $j < $nbPaiements; $j++) {
                    $estDernier = ($j === $nbPaiements - 1);
                    $montant = $estDernier
                        ? $montantRestant
                        : round($montantRestant / ($nbPaiements - $j), 2);

                    $montantRestant -= $montant;

                    $reservation->paiements()->create([
                        'numCarte' => $this->generateCardNumber(),
                        'montant' => $montant,
                        'payement_recieved_at' => now()->subDays(rand(0, 60)),
                        'methode_paiement' => $this->getRandomPaymentMethod(),
                        'statut' => $this->getRandomPaymentStatus(),
                        'reservation_id' => new ObjectId((string) $reservation->_id),
                    ]);

                    $totalPaiements++;
                }
            }
        }

        $this->command->info("   ✓ {$totalReservations} réservations créées");
        $this->command->info("   ✓ {$totalPaiements} paiements créés");
        $this->command->info("   → Montant total des réservations : " . number_format($totalMontant, 2) . " €");

        // Statistiques par statut de paiement
        $validesCount = \App\Models\Paiement::where('statut', 'validé')->count();
        $attenteCount = \App\Models\Paiement::where('statut', 'en_attente')->count();
        $refusesCount = \App\Models\Paiement::where('statut', 'refusé')->count();

        $this->command->info("   → Paiements validés : {$validesCount}");
        $this->command->info("   → Paiements en attente : {$attenteCount}");
        $this->command->info("   → Paiements refusés : {$refusesCount}");
    }

    private function generateCardNumber(): string
    {
        return '****' . rand(1000, 9999);
    }

    private function getRandomPaymentMethod(): string
    {
        $methods = ['carte', 'espèces', 'virement', 'cheque', 'paypal'];
        return $methods[array_rand($methods)];
    }

    private function getRandomPaymentStatus(): string
    {
        $statuses = ['validé', 'en_attente', 'refusé'];
        $weights = [80, 15, 5]; // 80% validé, 15% en attente, 5% refusé

        $rand = rand(1, 100);
        if ($rand <= $weights[0]) return $statuses[0];
        if ($rand <= $weights[0] + $weights[1]) return $statuses[1];
        return $statuses[2];
    }
}

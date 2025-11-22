<?php

namespace Database\Seeders;

use App\Models\Atelier;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $ateliers = Atelier::all();
        $clients = Client::all();

        if ($ateliers->isEmpty() || $clients->isEmpty()) {
            $this->command->error('   ✗ Erreur : Des ateliers et clients doivent exister');
            return;
        }

        $totalReservations = 0;
        $totalPaiements = 0;

        foreach ($ateliers as $atelier) {
            $nbReservations = rand(2, 6);

            foreach (range(1, $nbReservations) as $i) {
                $client = $clients->random();
                $nbPersonnes = rand(1, 4);

                $reservation = $atelier->reservations()->create([
                    'prix' => $atelier->prix * $nbPersonnes,
                    'nbPersonne' => $nbPersonnes,
                    'client_id' => $client->id,
                ]);

                $nbPaiements = rand(1, 3);
                $montantTotal = $reservation->prix;
                $montantRestant = $montantTotal;

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
                    ]);
                }
            }
        }
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

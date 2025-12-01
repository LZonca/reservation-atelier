<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Atelier;

class AtelierStatistics extends Component
{
    public $atelier;
    public $stats = [];

    protected $listeners = ['reservationUpdated' => 'refreshStats'];

    public function mount($atelier)
    {
        $this->atelier = $atelier;
        $this->calculateStats();
    }

    public function refreshStats()
    {
        // Recharger l'atelier avec les relations
        $this->atelier = Atelier::with(['reservations.client', 'reservations.paiement'])
            ->findOrFail($this->atelier->_id);
        $this->calculateStats();
    }

    public function calculateStats()
    {
        $reservations = $this->atelier->reservations;

        // Filtrer les réservations actives et annulées
        $activeReservations = $reservations->filter(fn($r) => empty($r->deleted_at));
        $deletedReservations = $reservations->filter(fn($r) => !empty($r->deleted_at));

        // Compteurs de base
        $this->stats['active_count'] = $activeReservations->count();
        $this->stats['deleted_count'] = $deletedReservations->count();
        $this->stats['total_count'] = $reservations->count();

        // Total de personnes (uniquement réservations actives)
        $this->stats['total_personnes'] = $activeReservations->sum(function($reservation) {
            return $reservation->nbPersonne ?? $reservation->nb_personne ?? 0;
        });

        // Revenu total (paiements validés uniquement)
        $validatedPayments = $activeReservations->filter(function($reservation) {
            $payment = $reservation->paiement;
            return $payment && in_array($payment->statut ?? '', ['validé', 'completed']);
        });

        $this->stats['total_revenue'] = $validatedPayments->sum(function($reservation) {
            return (float)($reservation->paiement->montant ?? 0);
        });

        // Paiements en attente
        $pendingPayments = $activeReservations->filter(function($reservation) {
            $payment = $reservation->paiement;
            return $payment && in_array($payment->statut ?? '', ['en_attente', 'pending']);
        });

        $this->stats['pending_count'] = $pendingPayments->count();
        $this->stats['pending_revenue'] = $pendingPayments->sum(function($reservation) {
            return (float)($reservation->paiement->montant ?? 0);
        });

        // Prix moyen par personne
        if ($this->stats['total_personnes'] > 0) {
            $this->stats['avg_price_per_person'] = $this->stats['total_revenue'] / $this->stats['total_personnes'];
        } else {
            $this->stats['avg_price_per_person'] = 0;
        }

        // Taux d'occupation (par rapport à la capacité de la salle si disponible)
        $salleCapacity = $this->atelier->salle->capacite ?? null;
        if ($salleCapacity && $salleCapacity > 0) {
            $this->stats['occupation_rate'] = min(100, ($this->stats['total_personnes'] / $salleCapacity) * 100);
        } else {
            // Sinon, on met 0 ou 100 selon s'il y a des réservations
            $this->stats['occupation_rate'] = $this->stats['active_count'] > 0 ? 100 : 0;
        }

        // Taux de paiement validé
        $totalExpectedRevenue = $activeReservations->sum(function($reservation) {
            $payment = $reservation->paiement;
            return $payment ? (float)($payment->montant ?? 0) : 0;
        });

        if ($totalExpectedRevenue > 0) {
            $this->stats['payment_rate'] = ($this->stats['total_revenue'] / $totalExpectedRevenue) * 100;
        } else {
            $this->stats['payment_rate'] = 0;
        }

        // Taille moyenne des groupes
        $this->stats['avg_group_size'] = $this->stats['active_count'] > 0
            ? $this->stats['total_personnes'] / $this->stats['active_count']
            : 0;

        // Répartition par méthode de paiement
        $this->stats['revenue_by_method'] = $this->getRevenueByPaymentMethod($activeReservations);
    }

    private function getRevenueByPaymentMethod($reservations)
    {
        $revenueByMethod = [];

        foreach ($reservations as $reservation) {
            $payment = $reservation->paiement;
            if ($payment && in_array($payment->statut ?? '', ['validé', 'completed'])) {
                $method = $payment->methode_paiement ?? 'non_specifié';
                $montant = (float)($payment->montant ?? 0);

                if (!isset($revenueByMethod[$method])) {
                    $revenueByMethod[$method] = [
                        'total' => 0,
                        'count' => 0
                    ];
                }
                $revenueByMethod[$method]['total'] += $montant;
                $revenueByMethod[$method]['count']++;
            }
        }

        return $revenueByMethod;
    }

    public function render()
    {
        return view('livewire.atelier-statistics');
    }
}

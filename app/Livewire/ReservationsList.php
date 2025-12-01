<?php

namespace App\Livewire;

use App\Models\Atelier;
use Livewire\Component;

class ReservationsList extends Component
{
    public $atelierId;
    public $atelier;
    public $reservations;
    public $stats = [];
    public $editReservationId = null;

    protected $listeners = ['$refresh', 'closeEditModal' => 'handleCloseEditModal'];

    public function mount($atelierId)
    {
        $this->atelierId = $atelierId;
        $this->loadData();
    }

    public function openEditModal($id)
    {
        $this->editReservationId = $id;
    }

    public function loadData()
    {
        $this->atelier = Atelier::with(['reservations.client', 'reservations.paiement', 'salle'])
            ->findOrFail($this->atelierId);

        // Récupérer toutes les réservations et les trier (actives en premier)
        $this->reservations = $this->atelier->reservations->sortBy(function($reservation) {
            return empty($reservation->deleted_at) ? 0 : 1;
        });

        // Calculer les statistiques
        $this->calculateStats();
    }

    public function calculateStats()
    {
        $activeReservations = $this->reservations->filter(fn($r) => empty($r->deleted_at));
        $deletedReservations = $this->reservations->filter(fn($r) => !empty($r->deleted_at));

        // Compteurs de base
        $this->stats['active_count'] = $activeReservations->count();
        $this->stats['deleted_count'] = $deletedReservations->count();
        $this->stats['total_count'] = $this->reservations->count();

        // Total de personnes (uniquement réservations actives)
        $this->stats['total_personnes'] = $activeReservations->sum(function($reservation) {
            return $reservation->nbPersonne ?? $reservation->nb_personne ?? 0;
        });

        // Revenu total (paiements validés uniquement)
        $this->stats['total_revenue'] = $activeReservations->sum(function($reservation) {
            $payment = $reservation->paiement;
            if ($payment && ($payment->statut === 'validé' || $payment->statut === 'validated')) {
                return (float)($payment->montant ?? 0);
            }
            return 0;
        });

        // Paiements en attente
        $pendingPayments = $activeReservations->filter(function($reservation) {
            $payment = $reservation->paiement;
            return $payment && ($payment->statut === 'en_attente' || $payment->statut === 'pending');
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
            $this->stats['occupation_rate'] = ($this->stats['total_personnes'] / $salleCapacity) * 100;
        } else {
            // Sinon, calculer par rapport au nombre de places totales réservables
            $this->stats['occupation_rate'] = $this->stats['active_count'] > 0 ? 100 : 0;
        }

        // Taux de paiement validé
        $totalExpectedRevenue = $activeReservations->sum(function($reservation) {
            return (float)($reservation->prix ?? 0);
        });

        if ($totalExpectedRevenue > 0) {
            $this->stats['payment_rate'] = ($this->stats['total_revenue'] / $totalExpectedRevenue) * 100;
        } else {
            $this->stats['payment_rate'] = 0;
        }

        // Statistiques supplémentaires
        $this->stats['revenue_by_method'] = $this->getRevenueByPaymentMethod($activeReservations);
        $this->stats['avg_group_size'] = $this->stats['active_count'] > 0
            ? $this->stats['total_personnes'] / $this->stats['active_count']
            : 0;
    }

    private function getRevenueByPaymentMethod($reservations)
    {
        $revenueByMethod = [];

        foreach ($reservations as $reservation) {
            $payment = $reservation->paiement;
            if ($payment && ($payment->statut === 'validé' || $payment->statut === 'validated')) {
                $method = $payment->methode_paiement ?? 'non_specifié';
                $montant = (float)($payment->montant ?? 0);

                if (!isset($revenueByMethod[$method])) {
                    $revenueByMethod[$method] = 0;
                }
                $revenueByMethod[$method] += $montant;
            }
        }

        return $revenueByMethod;
    }

    public function handleCloseEditModal()
    {
        $this->editReservationId = null;
        $this->loadData(); // Recharger les données pour mettre à jour les stats
    }

    public function render()
    {
        $atelier = $this->atelier;
        $reservations = collect($atelier->reservations ?? []);
        $total = $reservations->count();
        $active = $reservations->filter(fn($r) => empty($r['deleted_at']))->count();
        $cancelled = $reservations->filter(fn($r) => !empty($r['deleted_at']))->count();
        return view('livewire.reservations-list', [
            'atelier' => $atelier,
            'reservations' => $reservations,
            'total' => $total,
            'active' => $active,
            'cancelled' => $cancelled,
        ]);
    }
}

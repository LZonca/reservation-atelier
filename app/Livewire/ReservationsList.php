<?php

namespace App\Livewire;

use App\Models\Atelier;
use Livewire\Component;

class ReservationsList extends Component
{
    public $atelierId;
    public $atelier;
    public $reservations;
    public $editReservationId = null;

    protected $listeners = ['$refresh', 'closeEditModal' => 'handleCloseEditModal'];

    public function mount($atelierId)
    {
        $this->atelierId = $atelierId;
        $this->loadData();
    }

    public function openEditModal($id){
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
    }

    public function handleCloseEditModal()
    {
        $this->editReservationId = null;
    }

    public function render()
    {
        return view('livewire.reservations-list');
    }
}

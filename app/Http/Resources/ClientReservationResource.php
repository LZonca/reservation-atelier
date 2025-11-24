<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Reservation */
class ClientReservationResource extends JsonResource
{
    private $atelier;

    public function __construct($resource, $atelier = null)
    {
        parent::__construct($resource);
        $this->atelier = $atelier;
    }

    public function toArray(Request $request): array
    {
        return [
            'reservation_id' => $this->_id,
            'atelier' => $this->atelier ? [
                'id' => $this->atelier->_id,
                'nom' => $this->atelier->nom,
                'date' => $this->atelier->date,
                'prix' => $this->atelier->prix,
                'vip' => $this->atelier->vip,
            ] : null,
            'nbPersonne' => $this->nbPersonne,
            'prix_total' => $this->prix,
            'paiement' => $this->paiements ? new PaiementResource($this->paiements) : null,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Reservation */
class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Gérer le paiement embeded (peut être un tableau ou un objet)
        $paiement = null;
        if (is_array($this->resource) && isset($this->resource['paiements'])) {
            $paiement = $this->resource['paiements'];
        } elseif (isset($this->paiements)) {
            $paiement = $this->paiements;
        } elseif (is_array($this->resource) && isset($this->resource['paiement'])) {
            $paiement = $this->resource['paiement'];
        } elseif (isset($this->paiement)) {
            $paiement = $this->paiement;
        }

        return [
            'id' => $this->id ?? ($this->resource['_id'] ?? null),
            'prix' => $this->prix ?? ($this->resource['prix'] ?? null),
            'nbPersonne' => $this->nbPersonne ?? ($this->resource['nbPersonne'] ?? null),
            'client_id' => $this->client_id ?? ($this->resource['client_id'] ?? null),
            'atelier_id' => $this->atelier_id ?? ($this->resource['atelier_id'] ?? null),
            'paiement' => $paiement ? new PaiementResource($paiement) : null,
            'created_at' => $this->created_at ?? ($this->resource['created_at'] ?? null),
            'updated_at' => $this->updated_at ?? ($this->resource['updated_at'] ?? null),
        ];
    }
}

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
        return [
            'id' => $this->id,
            'prix' => $this->prix,
            'nbPersonne' => $this->nbPersonne,
            'client_id' => $this->client_id,
            'atelier_id' => $this->atelier_id,
            'paiement' => new PaiementResource($this->paiements),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Paiement */
class PaiementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'numCarte' => $this->numCarte ?? ($this->resource['numCarte'] ?? null),
            'montant' => $this->montant ?? ($this->resource['montant'] ?? null),
            'payement_recieved_at' => $this->payement_recieved_at ?? ($this->resource['payement_recieved_at'] ?? null),
            'methode_paiement' => $this->methode_paiement ?? ($this->resource['methode_paiement'] ?? null),
            'statut' => $this->statut ?? ($this->resource['statut'] ?? null),
            'created_at' => $this->created_at ?? ($this->resource['created_at'] ?? null),
        ];
    }
}

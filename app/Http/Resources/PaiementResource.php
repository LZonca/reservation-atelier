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
            'numCarte' => $this->numCarte,
            'montant' => $this->montant,
            'payement_recieved_at' => $this->payement_recieved_at,
            'methode_paiement' => $this->methode_paiement,
            'statut' => $this->statut,
            'created_at' => $this->created_at,
        ];
    }
}

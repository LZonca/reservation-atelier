<?php

namespace App\Http\Resources;

use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Salle */
class SalleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->getKey(),
            'nom' => $this->nom,
            'capacite' => $this->capacite,
            'categorie' => $this->categorie,
            'boutique' => $this->whenLoaded('boutique', function () {
                return new BoutiqueRessource($this->boutique);
            }, (string) ($this->boutique_id ?? '')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

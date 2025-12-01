<?php

namespace App\Http\Resources;

use App\Models\Intervenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Intervenant */
class IntervenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Gère à la fois les intervenants standalone et les intervenants embeded dans les ateliers
        $data = [
            'nom' => $this->nom ?? ($this->resource['nom'] ?? null),
            'prenom' => $this->prenom ?? ($this->resource['prenom'] ?? null),
        ];

        // Si c'est un intervenant embeded (array), récupérer infoContact directement
        if (is_array($this->resource) && isset($this->resource['infoContact'])) {
            $data['infoContact'] = $this->resource['infoContact'];
        }
        // Sinon, essayer de récupérer la relation infoContact (pour les standalone)
        elseif (method_exists($this->resource, 'infoContact')) {
            $data['infoContact'] = $this->infoContact;
        }
        // Fallback sur les anciens champs si présents
        else {
            $data['email'] = $this->email ?? ($this->resource['email'] ?? null);
            $data['telephone'] = $this->telephone ?? ($this->resource['telephone'] ?? null);
        }

        $data['created_at'] = $this->created_at ?? null;
        $data['updated_at'] = $this->updated_at ?? null;

        return $data;
    }
}

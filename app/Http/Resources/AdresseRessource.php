<?php


namespace App\Http\Resources;

use App\Models\Adresse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Adresse */
class AdresseRessource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'rue' => $this->rue,
            'numero' => $this->numero,
            'ville' => $this->ville,
            'code_postal' => $this->code_postal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}


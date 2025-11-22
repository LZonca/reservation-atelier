<?php


namespace App\Http\Resources;

use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Boutique */
class BoutiqueRessource extends JsonResource
{
    public static $wrap = 'boutique';

    public function toArray(Request $request): array
    {
        return [
            'nom' => $this->nom,
            'adresse' => new AdresseResource($this->adresse),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Atelier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Atelier */
class AtelierResource extends JsonResource
{
    public static $wrap = 'atelier';
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'date' => $this->date,
            'duree' => $this->duree,
            'prix' => $this->prix,
            'intervenant' => new IntervenantResource($this->intervenant),
            'salle' => new SalleResource($this->salle),
            'employe' => new UserResource($this->employe),
            'reservations' => ReservationResource::collection($this->reservations),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vip'=>$this->vip
        ];
    }
}

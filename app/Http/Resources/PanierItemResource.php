<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Atelier;

class PanierItemResource extends JsonResource
{
    public function toArray($request)
    {
        $atelier = Atelier::find($this->atelier_id);

        return [
            'atelier_id' => $this->atelier_id,
            'personnes' => $this->personnes,
            'added_at' => $this->added_at,
            'atelier' => $atelier ? new AtelierResource($atelier) : null,
        ];
    }
}

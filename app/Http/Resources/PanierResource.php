<?php

namespace App\Http\Resources;

use App\Models\Panier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Panier */
class PanierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'expires_at' => $this->expires_at,
            'client_id' => $this->client_id,
            'items' => PanierItemResource::collection($this->items),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

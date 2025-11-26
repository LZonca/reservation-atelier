<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CommentaireResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'commentaire' => $this->commentaire,
            'date' => $this->date,
            'client' => $this->client_id,
            'atelier' => $this->atelier_id,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

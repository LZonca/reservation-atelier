<?php

namespace App\Http\Resources;

use App\Models\InfoContact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InfoContact */
class InfoContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'mail' => $this->mail,
            'phone' => $this->phone,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
            'pinterest' => $this->pinterest,
            'bluesky' => $this->bluesky,
            'facebook' => $this->facebook,
            'website' => $this->website,
        ];
    }
}

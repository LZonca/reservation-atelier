<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Client */
class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Récupérer les réservations du client depuis tous les ateliers
        $ateliers = \App\Models\Atelier::where('reservations.client_id', $this->id)->get();
        $reservations = [];

        foreach ($ateliers as $atelier) {
            foreach ($atelier->reservations as $reservation) {
                if ($reservation->client_id == $this->id) {
                    $reservations[] = new ClientReservationResource($reservation, $atelier);
                }
            }
        }

        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'phone' => $this->phone,
            'panier' => $this->panier,
            'reservations' => $reservations,
            'total_reservations' => count($reservations),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'credit_fidelite' => $this->credit_fidelite
        ];
    }
}

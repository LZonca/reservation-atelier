<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use MongoDB\Laravel\Relations\EmbedsMany;
use MongoDB\Laravel\Relations\EmbedsOne;
use MongoDB\Laravel\Relations\HasMany;

class Client extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'panier',
        'created_at',
        'updated_at',
        'credit_fidelite'
    ];

    protected $casts = [
    ];

    protected $attributes = [
        'panier' => [],
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }

    /**
     * Vérifie si le panier est vide
     */
    public function panierIsEmpty(): bool
    {
        $panier = $this->panier ?? [];
        return empty($panier['ateliers']);
    }

    /**
     * Récupère le nombre d'items dans le panier
     */
    public function panierCount(): int
    {
        $panier = $this->panier ?? [];
        return count($panier['ateliers'] ?? []);
    }

    /**
     * Récupère le montant total du panier
     */
    public function panierTotal(): float
    {
        $panier = $this->panier ?? [];
        $total = 0;

        foreach (($panier['ateliers'] ?? []) as $item) {
            $prix = is_numeric($item['prix']) ? (float) $item['prix'] : 0;
            $quantity = is_numeric($item['quantity']) ? (int) $item['quantity'] : 0;
            $total += $prix * $quantity;
        }

        return $total;
    }

    /**
     * Vérifie si le panier a expiré
     */
    public function panierHasExpired(): bool
    {
        $panier = $this->panier ?? [];

        if (empty($panier['expires_at'])) {
            return false;
        }

        $expiresAt = $panier['expires_at'];

        // Convertir en Carbon si c'est une string ou un objet MongoDB UTCDateTime
        if (is_string($expiresAt)) {
            $expiresAt = \Carbon\Carbon::parse($expiresAt);
        } elseif ($expiresAt instanceof \MongoDB\BSON\UTCDateTime) {
            $expiresAt = $expiresAt->toDateTime();
            $expiresAt = \Carbon\Carbon::instance($expiresAt);
        }

        return now()->greaterThan($expiresAt);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model; // ← Corriger l'import
use MongoDB\Laravel\Relations\EmbedsMany;
use MongoDB\BSON\ObjectId;

class Panier extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'paniers';

    protected $fillable = [
        'expires_at',
        'client_id',
        'atelier_id',
        'personnes',
        'added_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'added_at' => 'datetime',
        'personnes' => 'integer',
    ];

    /**
     * Conversion automatique des IDs en ObjectId
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, ['client_id', 'atelier_id']) && $value !== null) {
            try {
                if (!$value instanceof ObjectId) {
                    $value = new ObjectId((string) $value);
                }
            } catch (\Exception $e) {
                \Log::warning("Impossible de convertir {$key} en ObjectId", [
                    'value' => $value,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Relation embedded : items du panier
     */
    public function items(): EmbedsMany
    {
        return $this->embedsMany(PanierItem::class, 'items');
    }

    /**
     * Relation : client propriétaire du panier
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


    /**
     * Vérifier si le panier a expiré
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Calculer le total du panier
     */
    public function getTotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->prix * $item->quantite;
        });
    }

}

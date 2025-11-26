<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class Paiement extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'paiements';

    protected $fillable = [
        'numCarte',
        'montant',
        'payement_recieved_at',
        'methode_paiement',
        'statut',
        'reservation_id',
    ];

    protected $casts = [
        'montant' => 'float',
        'payement_recieved_at' => 'datetime',
    ];

    /**
     * Conversion automatique de reservation_id en ObjectId
     */
    public function setAttribute($key, $value)
    {
        if ($key === 'reservation_id' && $value !== null) {
            try {
                if (!$value instanceof ObjectId) {
                    $value = new ObjectId((string) $value);
                }
            } catch (\Exception $e) {
                \Log::warning("Impossible de convertir reservation_id en ObjectId", [
                    'value' => $value,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Relations
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}

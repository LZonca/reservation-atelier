<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class Reservation extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reservations';

    protected $fillable = [
        'prix',
        'nbPersonne',
        'atelier_id',
        'client_id',
    ];

    protected $casts = [
        'prix' => 'float',
        'nbPersonne' => 'integer',
    ];

    /**
     * Conversion automatique des IDs en ObjectId
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, ['atelier_id', 'client_id']) && $value !== null) {
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
     * Relations
     */
    public function atelier()
    {
        return $this->belongsTo(Atelier::class, 'atelier_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'reservation_id');
    }

}

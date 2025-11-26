<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class PanierItem extends Model
{
    protected $fillable = [
        'atelier_id',
        'nom',
        'prix',
        'quantite',
        'added_at',
    ];

    protected $casts = [
        'prix' => 'float',
        'quantite' => 'integer',
        'added_at' => 'datetime',
    ];

    /**
     * Conversion automatique de atelier_id en ObjectId
     */
    public function setAttribute($key, $value)
    {
        if ($key === 'atelier_id' && $value !== null) {
            try {
                if (!$value instanceof ObjectId) {
                    $value = new ObjectId((string) $value);
                }
            } catch (\Exception $e) {
                \Log::warning("Impossible de convertir atelier_id en ObjectId", [
                    'value' => $value,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Relation vers l'atelier (si besoin)
     */
    public function atelier()
    {
        return $this->belongsTo(Atelier::class, 'atelier_id');
    }

}

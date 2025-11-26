<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

class Commentaire extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'commentaires';

    protected $fillable = [
        'commentaire',
        'note',
        'atelier_id',
        'client_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Conversion automatique des IDs en ObjectId lors de l'assignation
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, ['atelier_id', 'client_id']) && $value !== null) {
            try {
                // Vérifier si c'est déjà un ObjectId
                if (!$value instanceof ObjectId) {
                    $value = new ObjectId((string) $value);
                }
            } catch (\Exception $e) {
                Log::warning("Impossible de convertir {$key} en ObjectId", [
                    'key' => $key,
                    'value' => $value,
                    'error' => $e->getMessage()
                ]);
                // En cas d'échec, laisser la valeur telle quelle
                // Le parent gérera l'erreur si nécessaire
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Relations
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function atelier()
    {
        return $this->belongsTo(Atelier::class, 'atelier_id');
    }
}

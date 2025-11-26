<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class Atelier extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'ateliers';

    protected $fillable = [
        'nom',
        'date',
        'description',
        'duree',
        'prix',
        'employe_id',
        'salle_id',
        'category',
        'intervenant',
        'vip',
    ];

    protected $casts = [
        'date' => 'datetime',
        'duree' => 'integer',
        'prix' => 'float',
        'vip' => 'boolean',
        'intervenant' => 'array',
    ];

    /**
     * Conversion automatique des IDs en ObjectId
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, ['employe_id', 'salle_id']) && $value !== null) {
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
    public function employe()
    {
        return $this->belongsTo(User::class, 'employe_id');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'atelier_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_id');
    }

}

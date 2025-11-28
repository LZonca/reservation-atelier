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
     * Relations
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

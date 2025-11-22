<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\DocumentModel;

class Paiement extends Model
{
    use HasFactory, DocumentModel;

    protected $fillable = [
        'numCarte',
        'montant',
        'payement_recieved_at',
        'methode_paiement',
        'statut',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'payement_recieved_at' => 'datetime',
        'montant' => 'decimal:2',
    ];
}

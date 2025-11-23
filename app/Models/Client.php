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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Relations\BelongsTo;
use App\Models\Boutique;

class Salle extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    protected $connection = 'mongodb';
    protected string $collection = 'salles';

    protected $fillable = [
        'nom',
        'capacite',
        'categorie',
        'boutique',
    ];

    // Relation vers le modèle Boutique. On n'utilise pas le nom `boutique` pour la relation
    // car le document Salle contient déjà un attribut `boutique` (l'ID). La relation
    // s'appelle donc `boutiqueModel` pour éviter la collision.
    public function boutiqueModel(): BelongsTo
    {
        return $this->belongsTo(Boutique::class, 'boutique', '_id');
    }

    public function ateliers()
    {
        return $this->hasMany(Atelier::class);
    }
}

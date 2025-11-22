<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;

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
    ];
}

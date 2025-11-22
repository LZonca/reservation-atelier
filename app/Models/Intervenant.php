<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;

class Intervenant extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'created_at',
        'updated_at',
    ];
}

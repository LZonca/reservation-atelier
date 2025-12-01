<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Relations\EmbedsOne;

class Intervenant extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    protected $fillable = [
        'nom',
        'prenom',
        'infoContact',
        'email',
        'telephone',
        'created_at',
        'updated_at',
    ];


    public function adresse(): EmbedsOne
    {
        return $this->embedsOne(Adresse::class);
    }

    public function infoContact(): EmbedsOne
    {
        return $this->embedsOne(InfoContact::class);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\EmbedsOne;

class Reservation extends Model
{
    use HasFactory;
    use DocumentModel;
    use SoftDeletes;

    protected $fillable = [
        'prix',
        'nbPersonne',
    ];

    public function atelier()
    {
        return $this->belongsTo(Atelier::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function paiement()
    {
        return $this->embedsOne(Paiement::class, 'paiements');
    }
}

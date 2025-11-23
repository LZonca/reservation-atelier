<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\EmbedsOne;

class Reservation extends Model
{
    use HasFactory;
    use DocumentModel;
    protected string $collection = 'reservations';

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

    public function paiements(): EmbedsOne
    {
        return $this->embedsOne(Paiement::class);
    }
}

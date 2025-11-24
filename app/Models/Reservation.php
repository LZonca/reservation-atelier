<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\EmbedsOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use DocumentModel;
    use SoftDeletes;

    protected string $collection = 'reservations';

    protected $fillable = [
        'prix',
        'nbPersonne',
        'client_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
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

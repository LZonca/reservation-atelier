<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Relations\EmbedsMany;

class Panier extends Model
{

    use HasFactory;
    use DocumentModel;
    protected $fillable = [
        'expires_at',
        'client_id',
        'atelier_id',
        'personnes',
        'added_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function items(): EmbedsMany
    {
        return $this->embedsMany(PanierItem::class, 'items');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

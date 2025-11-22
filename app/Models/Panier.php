<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Relations\EmbedsMany;

class Panier extends Model
{

    use HasFactory;

    protected $fillable = [
        'expires_at',
    ];

    public function ateliers(): EmbedsMany
    {
        return $this->embedsMany(Atelier::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}

<?php

declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\EmbedsMany;

class Atelier extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    // Use the MongoDB connection and specify collection name
    protected $connection = 'mongodb';
    protected string $collection = 'ateliers';

    protected $fillable = [
        'nom',
        'date',
        'description',
        'duree',
        'prix',
        'created_at',
        'updated_at',
    ];


    public function reservations(): EmbedsMany
    {
        return $this->embedsMany(Reservation::class);
    }

    public function employe(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function intervenant()
    {
        return $this->embedsOne(Intervenant::class);
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }


    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }
}

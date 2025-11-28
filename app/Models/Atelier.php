<?php

declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\EmbedsMany;
use MongoDB\Laravel\Relations\HasMany;
use MongoDB\Laravel\Relations\HasOne;

class Atelier extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    protected $connection = 'mongodb';
    protected string $collection = 'ateliers';

    protected $fillable = [
        'nom',
        'date',
        'description',
        'duree',
        'prix',
        'salle_id',
        'employe_id',
        'intervenant',
        'created_at',
        'updated_at',
        'vip'
    ];

    /**
     * Type casting pour certains attributs
     */
    protected $casts = [
        'prix' => 'float',
        'vip' => 'boolean',
        'date' => 'datetime',
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

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }

    /**
     * Récupère uniquement les réservations actives (non soft-deleted)
     *
     * @return \Illuminate\Support\Collection
     */
    public function activeReservations()
    {
        return $this->reservations->filter(function($reservation) {
            return empty($reservation->deleted_at);
        });
    }

    /**
     * Calcule la capacité restante en prenant en compte uniquement les réservations actives
     */
    public function remainingCapacity(): int
    {
        // Compter uniquement les réservations non supprimées
        $reserved = $this->activeReservations()->sum('nbPersonne');

        $salleCapacite = $this->salle ? ($this->salle->capacite ?? 0) : ($this->capacite ?? 0);

        return max(0, $salleCapacite - $reserved);
    }

    /**
     * Récupère les réservations supprimées (soft-deleted)
     *
     * @return \Illuminate\Support\Collection
     */
    public function deletedReservations()
    {
        return $this->reservations->filter(function($reservation) {
            return !empty($reservation->deleted_at);
        });
    }

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;
    protected string $collection = 'reservations';

    protected $fillable = [
        'prix',
        'nbPersonne',
    ];
}

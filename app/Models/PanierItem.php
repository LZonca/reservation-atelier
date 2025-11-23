<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PanierItem extends Model
{
    protected $fillable = [
        'atelier_id',
        'personnes',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];
}

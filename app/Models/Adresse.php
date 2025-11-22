<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Adresse extends Model
{
    use HasFactory;

    protected string $collection = 'adresses';

    protected $fillable = [
        'rue',
        'numero',
        'ville',
        'code_postal',
        'created_at',
        'updated_at',
    ];

}


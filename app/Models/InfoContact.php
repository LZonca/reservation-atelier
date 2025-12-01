<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;

class InfoContact extends EloquentModel
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = [
        'email',
        'telephone',
        'website',
        'youtube',
        'instagram',
        'facebook',
        'twitter',
        'pinterest',
        'bluesky',
    ];

    protected $casts = [
        'email' => 'string',
        'telephone' => 'string',
        'website' => 'string',
        'youtube' => 'string',
        'instagram' => 'string',
        'facebook' => 'string',
        'twitter' => 'string',
        'pinterest' => 'string',
        'bluesky' => 'string',
    ];
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoContact extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'mail',
        'phone',
        'twitter',
        'instagram',
        'youtube',
        'pinterest',
        'bluesky',
        'facebook',
        'website',
    ];
}

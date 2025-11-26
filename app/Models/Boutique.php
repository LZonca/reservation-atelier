<?php


declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Relations\EmbedsOne;

class Boutique extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    // Use the MongoDB connection and specify collection name
    protected $connection = 'mongodb';
    protected string $collection = 'boutique';

    protected $fillable = [
        'nom',
        'created_at',
        'updated_at',
    ];

    public function adresse(): EmbedsOne
    {
        return $this->embedsOne(Adresse::class);
    }


}


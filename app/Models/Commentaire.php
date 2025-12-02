<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;

class Commentaire extends EloquentModel
{
    /** @use HasFactory<\Database\Factories\CommentaireFactory> */
    use HasFactory, DocumentModel;

    protected $connection = 'mongodb';
    protected string $collection = 'commentaires';

    protected $fillable = [
        'commentaire',
        'client',
        'client_id',
        'atelier_id',
        'note',
        'created_at',
        'updated_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function atelier()
    {
        return $this->belongsTo(Atelier::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }
}

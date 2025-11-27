<?php

// ============================================
// App/Http/Controllers/AtelierController.php
// ============================================

namespace App\Http\Controllers;

use App\Http\Requests\AtelierRequest;
use App\Http\Resources\AtelierResource;
use App\Models\Atelier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Commentaire;
use App\Http\Resources\CommentaireResource;
use App\Http\Requests\CommentaireRequest;
use MongoDB\BSON\ObjectId;

class AtelierController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $ateliers = Atelier::with(['salle', 'employe'])->get();
        return AtelierResource::collection($ateliers);
    }

    public function store(AtelierRequest $request)
    {
        $atelier = Atelier::create($request->validated());
        return new AtelierResource($atelier);
    }

    public function addComment(Atelier $atelier, CommentaireRequest $request)
    {
        // Créer le commentaire avec ObjectId
        $commentaire = $atelier->commentaires()->create([
            'commentaire' => $request->input('commentaire'),
            'note' => $request->input('note'),
            'atelier_id' => new ObjectId((string) $atelier->_id),
            'client_id' => new ObjectId($request->input('client_id')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return new CommentaireResource($commentaire);
    }

    public function getComments(Atelier $atelier)
    {
        $commentaires = $atelier->commentaires()->with('client')->get();
        return CommentaireResource::collection($commentaires);
    }

    public function show($id)
    {
        $atelier = Atelier::with(['reservations.client', 'salle', 'employe'])->findOrFail($id);
        return new AtelierResource($atelier);
    }

    public function update(AtelierRequest $request, $id)
    {
        \Log::info('Update atelier', [
            'id' => $id,
            'data' => $request->validated()
        ]);

        $atelier = Atelier::findOrFail($id);
        $atelier->update($request->validated());

        \Log::info('Atelier updated', ['atelier' => $atelier->toArray()]);

        return new AtelierResource($atelier);
    }

    public function destroy(Atelier $atelier)
    {
        $atelier->delete();
        return response()->json(['message' => 'Atelier supprimé avec succès.']);
    }
}

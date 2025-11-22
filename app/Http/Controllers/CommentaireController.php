<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
// use App\Http\Requests\StoreCommentaireRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCommentaireRequest;
use App\Http\Resources\CommentaireResource;

class CommentaireController extends Controller
{
    public function index()
    {
        $commentaires = Commentaire::all();
        return CommentaireResource::collection($commentaires);
    }

    public function show(Commentaire $commentaire)
    {
        $commentaire->load(['client', 'atelier']);
        return new CommentaireResource($commentaire);
    }

    public function update(UpdateCommentaireRequest $request, Commentaire $commentaire)
    {
        $commentaire->update($request->validated());
        return new CommentaireResource($commentaire);
    }

    public function destroy(Commentaire $commentaire)
    {
        $commentaire->delete();
        
        return response()->json();
    }
}

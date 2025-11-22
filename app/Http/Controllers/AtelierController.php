<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtelierRequest;
use App\Http\Resources\AtelierResource;
use App\Models\Atelier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Commentaire;
use App\Models\Client;
use App\Http\Resources\CommentaireResource;
use Illuminate\Http\Request;
use App\Http\Requests\CommentaireRequest;

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
        $this->authorize('create', Atelier::class);

        return new AtelierResource(Atelier::create($request->validated()));
    }

    public function addComment(Atelier $atelier, CommentaireRequest $request)
    {
        $commentaire = new Commentaire($request->validated());

        $commentaire->atelier_id = $atelier->id;
        $commentaire->client_id = $request->input('client_id');
        $commentaire->date = now();

        $commentaire->save();

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

    public function update(AtelierRequest $request, Atelier $atelier)
    {
        $this->authorize('update', $atelier);

        $atelier->update($request->validated());

        return new AtelierResource($atelier);
    }

    public function destroy(Atelier $atelier)
    {
        $this->authorize('delete', $atelier);

        $atelier->delete();

        return response()->json();
    }
}

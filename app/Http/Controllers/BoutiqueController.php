<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoutiqueRequest;
use App\Http\Resources\BoutiqueRessource;
use App\Models\Boutique;

class BoutiqueController
{
    public function index()
    {
        return BoutiqueRessource::collection(Boutique::all());
    }

    public function store(BoutiqueRequest $request)
    {
        return new BoutiqueRessource(Boutique::create($request->validated()));
    }

    public function show($id)
    {
        $boutique = Boutique::with(['adresse'])->findOrFail($id);
        return new BoutiqueRessource($boutique);
    }
    public function update(BoutiqueRequest $request, Boutique $boutique)
    {
        $boutique->update($request->validated());
        return new BoutiqueRessource($boutique);
    }

    public function destroy(Boutique $boutique)
    {
        $boutique->delete();
        return response()->json();
    }
}

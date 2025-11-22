<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdresseRequest;
use App\Http\Resources\AdresseRessource;
use App\Models\Adresse;


class AdresseController
{
    public function index(){
        return AdresseRessource::collection(Adresse::all());
    }

    public function store(AdresseRequest $request){
        return new AdresseRessource(Adresse::create($request->validated()));
    }

    public function show(Adresse $adresse)
    {
        return new AdresseRessource($adresse);
    }

    public function update(AdresseRequest $request, Adresse $adresse)
    {
        $adresse->update($request->validated());

        return new AdresseRessource($adresse);
    }

    public function destroy(Adresse $adresse)
    {
        $adresse->delete();

        return response()->json();
    }
}

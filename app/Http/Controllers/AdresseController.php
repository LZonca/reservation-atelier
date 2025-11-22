<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdresseRequest;
use App\Http\Resources\AdresseResource;
use App\Models\Adresse;


class AdresseController
{
    public function index(){
        return AdresseResource::collection(Adresse::all());
    }

    public function store(AdresseRequest $request){
        return new AdresseResource(Adresse::create($request->validated()));
    }

    public function show(Adresse $adresse)
    {
        return new AdresseResource($adresse);
    }

    public function update(AdresseRequest $request, Adresse $adresse)
    {
        $adresse->update($request->validated());

        return new AdresseResource($adresse);
    }

    public function destroy(Adresse $adresse)
    {
        $adresse->delete();

        return response()->json();
    }
}

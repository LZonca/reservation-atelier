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
        $data = $request->validated();

        // Préparer les données pour la création
        $boutiqueData = ['nom' => $data['nom']];

        // Ajouter infoContact si fourni
        if (!empty($data['infoContact']) && array_filter($data['infoContact'])) {
            $boutiqueData['infoContact'] = array_filter($data['infoContact']);
        }

        // créer la boutique
        $boutique = Boutique::create($boutiqueData);

        // créer l'adresse embed si fournie
        if (!empty($data['adresse']) && array_filter($data['adresse'])) {
            $boutique->adresse()->create($data['adresse']);
        }

        return new BoutiqueRessource($boutique);
    }

    public function show($id)
    {
        $boutique = Boutique::with(['adresse'])->findOrFail($id);
        return new BoutiqueRessource($boutique);
    }
    public function update(BoutiqueRequest $request, Boutique $boutique)
    {
        $data = $request->validated();

        // Préparer les données pour la mise à jour
        $updateData = ['nom' => $data['nom']];

        // Ajouter infoContact si fourni
        if (!empty($data['infoContact'])) {
            // Filtrer les valeurs vides
            $updateData['infoContact'] = array_filter($data['infoContact'], function($value) {
                return $value !== null && $value !== '';
            });
        }

        $boutique->update($updateData);

        // gérer l'adresse embed
        if (!empty($data['adresse']) && array_filter($data['adresse'])) {
            if ($boutique->adresse) {
                // update existing embed
                $boutique->adresse()->update($data['adresse']);
            } else {
                $boutique->adresse()->create($data['adresse']);
            }
        }

        return new BoutiqueRessource($boutique);
    }

    public function destroy(Boutique $boutique)
    {
        $boutique->delete();
        return response()->json();
    }
}

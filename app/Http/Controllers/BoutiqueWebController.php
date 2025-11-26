<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boutique;
use App\Models\Adresse;

class BoutiqueWebController extends Controller
{
    public function index()
    {
        $boutiques = Boutique::orderBy('nom')->paginate(20);
        return view('boutiques.index', compact('boutiques'));
    }

    public function create()
    {
        return view('boutiques.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse.rue' => 'nullable|string|max:255',
            'adresse.numero' => 'nullable|string|max:50',
            'adresse.ville' => 'nullable|string|max:255',
            'adresse.code_postal' => 'nullable|string|max:20',
        ]);

        // créer la boutique
        $boutique = Boutique::create(['nom' => $data['nom']]);

        // créer l'adresse embed si fournie
        if (!empty($data['adresse']) && array_filter($data['adresse'])) {
            $boutique->adresse()->create($data['adresse']);
        }

        return redirect(url('/boutiques'))->with('success', 'Boutique créée.');
    }

    public function show($id)
    {
        $boutique = Boutique::findOrFail($id);
        return view('boutiques.show', compact('boutique'));
    }

    public function edit($id)
    {
        $boutique = Boutique::findOrFail($id);
        return view('boutiques.edit', compact('boutique'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse.rue' => 'nullable|string|max:255',
            'adresse.numero' => 'nullable|string|max:50',
            'adresse.ville' => 'nullable|string|max:255',
            'adresse.code_postal' => 'nullable|string|max:20',
        ]);

        $boutique = Boutique::findOrFail($id);
        $boutique->update(['nom' => $data['nom']]);

        // gérer l'adresse embed
        if (!empty($data['adresse']) && array_filter($data['adresse'])) {
            if ($boutique->adresse) {
                // update existing embed
                $boutique->adresse()->update($data['adresse']);
            } else {
                $boutique->adresse()->create($data['adresse']);
            }
        }

        return redirect(url('/boutiques'))->with('success', 'Boutique mise à jour.');
    }

    public function destroy($id)
    {
        $boutique = Boutique::findOrFail($id);
        $boutique->delete();

        return redirect(url('/boutiques'))->with('success', 'Boutique supprimée.');
    }
}

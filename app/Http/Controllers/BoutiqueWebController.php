<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boutique;
use App\Models\Adresse;
use App\Models\User;

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

        // Charger les employés associés à cette boutique via la relation
        $employes = \App\Models\User::where('boutique_id', new \MongoDB\BSON\ObjectId((string) $id))->get();

        // Charger les salles : d'abord les embarquées, sinon les salles liées par boutique_id
        $sallesEmbedded = collect($boutique->salles ?? []);

        if ($sallesEmbedded->isEmpty()) {
            // Utiliser la relation hasMany si pas de salles embarquées
            $salles = \App\Models\Salle::where('boutique_id', new \MongoDB\BSON\ObjectId((string) $id))->get();
        } else {
            $salles = $sallesEmbedded;
        }

        return view('boutiques.show', compact('boutique', 'employes', 'salles'));
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

    /**
     * Affecter un employé à une boutique
     */
    public function affectEmploye(Request $request, $boutiqueId)
    {
        $request->validate([
            'employe_id' => 'required|string',
        ]);

        $boutique = Boutique::findOrFail($boutiqueId);
        $employe = User::findOrFail($request->employe_id);

        // Affecter l'employé à la boutique
        $employe->boutique_id = new \MongoDB\BSON\ObjectId((string) $boutiqueId);
        $employe->save();

        return redirect(url('/boutiques/' . $boutiqueId))
            ->with('success', "Employé {$employe->name} affecté à la boutique avec succès.");
    }

    /**
     * Retirer un employé d'une boutique
     */
    public function retirerEmploye($boutiqueId, $employeId)
    {
        $employe = User::findOrFail($employeId);
        $employe->boutique_id = null;
        $employe->save();

        return redirect(url('/boutiques/' . $boutiqueId))
            ->with('success', "Employé {$employe->name} retiré de la boutique.");
    }

    /**
     * Affecter une salle à une boutique
     */
    public function affectSalle(Request $request, $boutiqueId)
    {
        $request->validate([
            'salle_id' => 'required|string',
        ]);

        $boutique = Boutique::findOrFail($boutiqueId);
        $salle = \App\Models\Salle::findOrFail($request->salle_id);

        // Affecter la salle à la boutique
        $salle->boutique_id = new \MongoDB\BSON\ObjectId((string) $boutiqueId);
        $salle->save();

        return redirect(url('/boutiques/' . $boutiqueId))
            ->with('success', "Salle {$salle->nom} affectée à la boutique avec succès.");
    }

    /**
     * Retirer une salle d'une boutique
     */
    public function retirerSalle($boutiqueId, $salleId)
    {
        $salle = \App\Models\Salle::findOrFail($salleId);
        $salle->boutique_id = null;
        $salle->save();

        return redirect(url('/boutiques/' . $boutiqueId))
            ->with('success', "Salle {$salle->nom} retirée de la boutique.");
    }
}

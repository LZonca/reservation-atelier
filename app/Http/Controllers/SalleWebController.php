<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salle;
use App\Models\Boutique;

class SalleWebController extends Controller
{
    public function index()
    {
        $salles = Salle::with('boutiqueModel')->orderBy('nom')->paginate(20);
        return view('salles.index', compact('salles'));
    }

    public function create()
    {
        $boutiques = Boutique::orderBy('nom')->get();
        return view('salles.create', compact('boutiques'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'nullable|integer|min:0',
            'adresse' => 'nullable|string|max:500',
            'boutique' => ['nullable', 'string'],
        ]);

        $salle = Salle::create($data);

        return redirect(url('/salles'))->with('success', 'Salle créée.');
    }

    public function show($id)
    {
        $salle = Salle::with('boutiqueModel')->findOrFail($id);
        return view('salles.show', compact('salle'));
    }

    public function edit($id)
    {
        $salle = Salle::findOrFail($id);
        $boutiques = Boutique::orderBy('nom')->get();
        return view('salles.edit', compact('salle', 'boutiques'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'nullable|integer|min:0',
            'adresse' => 'nullable|string|max:500',
            'boutique' => ['nullable', 'string'],
        ]);

        $salle = Salle::findOrFail($id);
        $salle->update($data);

        return redirect(url('/salles'))->with('success', 'Salle mise à jour.');
    }

    public function destroy($id)
    {
        $salle = Salle::findOrFail($id);
        $salle->delete();

        return redirect(url('/salles'))->with('success', 'Salle supprimée.');
    }
}

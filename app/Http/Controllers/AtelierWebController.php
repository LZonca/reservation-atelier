<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atelier;
use App\Models\Reservation;
use App\Models\Salle;

class AtelierWebController extends Controller
{
    // Liste des ateliers (page web)
    public function index()
    {
        $ateliers = Atelier::with('salle')->orderBy('created_at', 'desc')->paginate(12);
        return view('ateliers.index', compact('ateliers'));
    }

    // Formulaire de création
    public function create()
    {
        $salles = Salle::orderBy('nom')->get();
        return view('ateliers.create', compact('salles'));
    }

    // Stocker un nouvel atelier
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'duree' => 'nullable|string|max:100',
            'prix' => 'nullable|numeric',
            'salle_id' => 'nullable|string',
            'vip' => 'sometimes|boolean',
        ]);

        $data['vip'] = !empty($data['vip']);

        $atelier = Atelier::create($data);

        return redirect(url('/ateliers'))->with('success', 'Atelier créé avec succès.');
    }

    // Afficher un atelier
    public function show($id)
    {
        $atelier = Atelier::with('salle')->findOrFail($id);

        // récupérer les réservations standalone liées
        $external = Reservation::where('atelier_id', $atelier->getKey())->orderBy('created_at', 'desc')->get();

        // récupère les réservations embarquées si présentes
        $embedded = collect();
        if ($atelier->reservations) {
            if (is_array($atelier->reservations)) {
                $embedded = collect($atelier->reservations);
            } else {
                $embedded = $atelier->reservations instanceof \Illuminate\Support\Collection ? $atelier->reservations : collect($atelier->reservations);
            }
        }

        $reservations = $external->merge($embedded);

        return view('ateliers.show', compact('atelier', 'reservations'));
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $atelier = Atelier::findOrFail($id);
        $salles = Salle::orderBy('nom')->get();
        return view('ateliers.edit', compact('atelier', 'salles'));
    }

    // Mettre à jour
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'duree' => 'nullable|string|max:100',
            'prix' => 'nullable|numeric',
            'salle_id' => 'nullable|string',
            'vip' => 'sometimes|boolean',
        ]);

        $data['vip'] = !empty($data['vip']);

        $atelier = Atelier::findOrFail($id);
        $atelier->update($data);

        return redirect(url('/ateliers'))->with('success', 'Atelier mis à jour.');
    }

    // Supprimer
    public function destroy($id)
    {
        $atelier = Atelier::findOrFail($id);
        $atelier->delete();

        return redirect(url('/ateliers'))->with('success', 'Atelier supprimé.');
    }

    // Afficher les réservations pour un atelier (collection + embedded)
    public function reservations($id)
    {
        $atelier = Atelier::findOrFail($id);

        // Réservations standalone liées à cet atelier (collection 'reservations')
        $external = Reservation::where('atelier_id', $atelier->getKey())->orderBy('created_at', 'desc')->get();

        // Réservations embarquées dans l'atelier (si embedsMany) — normaliser en collection
        $embedded = collect();
        if ($atelier->reservations) {
            if (is_array($atelier->reservations)) {
                $embedded = collect($atelier->reservations);
            } else {
                $embedded = $atelier->reservations instanceof \Illuminate\Support\Collection ? $atelier->reservations : collect($atelier->reservations);
            }
        }

        // Fusionner les deux sources (external puis embedded)
        $reservations = $external->merge($embedded);

        return view('ateliers.reservations', compact('atelier', 'reservations'));
    }

    // Stocker une réservation depuis le site (formulaire web)
    public function storeReservation(Request $request, $id)
    {
        $atelier = Atelier::findOrFail($id);

        $data = $request->validate([
            'client_name' => ['nullable','string','max:255'],
            'client_id' => ['nullable','string'],
            'nbPersonne' => ['required','integer','min:1'],
            'prix' => ['nullable','numeric'],
        ]);

        $reservation = new Reservation();
        // assigner les champs disponibles
        $reservation->nbPersonne = $data['nbPersonne'];
        if (isset($data['prix'])) $reservation->prix = (float)$data['prix'];
        if (!empty($data['client_id'])) $reservation->client_id = $data['client_id'];
        if (!empty($data['client_name'])) $reservation->client_name = $data['client_name'];

        // lier à l'atelier (champ atelier_id)
        $reservation->atelier_id = $atelier->getKey();

        $reservation->save();

        return redirect(url('/ateliers/' . $atelier->getKey() . '/reservations'))->with('success', 'Réservation créée.');
    }
}

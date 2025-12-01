<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atelier;
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
        // Charger toutes les salles (seront filtrées côté client par JavaScript)
        $sallesParBoutique = \App\Models\Salle::with('boutique')
            ->orderBy('nom')
            ->get()
            ->groupBy(function($salle) {
                return $salle->boutique ? $salle->boutique->nom : 'Sans boutique';
            });

        // Charger les intervenants (employés)
        $intervenants = \App\Models\User::orderBy('name')->get();

        return view('ateliers.create', compact('sallesParBoutique', 'intervenants'));
    }

    // Stocker un nouvel atelier
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'duree' => 'nullable|integer|min:1',
            'prix' => 'nullable|numeric',
            'salle_id' => 'nullable|string',
            'employe_id' => 'nullable|string',
            'vip' => 'sometimes|boolean',
        ]);

        // Vérifier la disponibilité de la salle si salle_id, date et durée sont fournis
        if (!empty($data['salle_id']) && !empty($data['date']) && !empty($data['duree'])) {
            $salle = \App\Models\Salle::findOrFail($data['salle_id']);

            if (!$salle->isDisponible($data['date'], $data['duree'])) {
                return back()
                    ->withInput()
                    ->withErrors(['salle_id' => 'Cette salle n\'est pas disponible pour le créneau sélectionné.']);
            }
        }

        $data['vip'] = !empty($data['vip']);

        $atelier = Atelier::create($data);

        return redirect(url('/ateliers'))->with('success', 'Atelier créé avec succès.');
    }

    // Afficher un atelier
    public function show($id)
    {
        $atelier = Atelier::with('salle')->findOrFail($id);

        // Réservations embarquées dans l'atelier
        $reservations = $atelier->reservations ?? collect();

        // Charger les clients pour les réservations
        if ($reservations->isNotEmpty()) {
            $reservations->load('client');
        }

        return view('ateliers.show', compact('atelier', 'reservations'));
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $atelier = Atelier::findOrFail($id);

        // Charger les salles groupées par boutique
        $sallesParBoutique = \App\Models\Salle::with('boutique')
            ->orderBy('nom')
            ->get()
            ->groupBy(function($salle) {
                return $salle->boutique ? $salle->boutique->nom : 'Sans boutique';
            });

        // Charger les intervenants (employés)
        $intervenants = \App\Models\User::orderBy('name')->get();

        return view('ateliers.edit', compact('atelier', 'sallesParBoutique', 'intervenants'));
    }

    // Mettre à jour
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'duree' => 'nullable|integer|min:1',
            'prix' => 'nullable|numeric',
            'salle_id' => 'nullable|string',
            'employe_id' => 'nullable|string',
            'vip' => 'sometimes|boolean',
        ]);

        $atelier = Atelier::findOrFail($id);

        // Vérifier la disponibilité de la salle si salle_id, date et durée sont fournis
        if (!empty($data['salle_id']) && !empty($data['date']) && !empty($data['duree'])) {
            $salle = \App\Models\Salle::findOrFail($data['salle_id']);

            // Exclure l'atelier en cours de modification de la vérification
            if (!$salle->isDisponible($data['date'], $data['duree'], $id)) {
                return back()
                    ->withInput()
                    ->withErrors(['salle_id' => 'Cette salle n\'est pas disponible pour le créneau sélectionné.']);
            }
        }

        $data['vip'] = !empty($data['vip']);
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

        // Réservations embarquées dans l'atelier
        $reservations = $atelier->reservations ?? collect();

        // Charger les clients pour les réservations
        if ($reservations->isNotEmpty()) {
            $reservations->load('client');
        }

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

        // Création d'une vraie Reservation (DocumentModel)
        $reservation = new \App\Models\Reservation([
            'nbPersonne' => $data['nbPersonne'],
            'prix' => $data['prix'] ?? null,
            'client_id' => $data['client_id'] ?? null,
            'client_name' => $data['client_name'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Facultatif : créer un paiement embedded automatiquement
        $reservation->paiement()->associate(new \App\Models\Paiement([
            'montant' => $reservation->prix,
            'statut' => 'en_attente',
            'methode_paiement' => 'carte',
            'payement_recieved_at' => null,
        ]));

        // Sauvegarde embedded dans l'atelier
        $atelier->reservations()->save($reservation);

        return redirect(url('/ateliers/' . $atelier->getKey() . '/reservations'))
            ->with('success', 'Réservation créée.');
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Atelier;
use App\Models\Reservation;
use App\Models\Paiement;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return ClientResource::collection(Client::all());
    }

    public function store(ClientRequest $request)
    {
        $client = Client::create($request->validated());
        $client->credit_fidelite = 0;
        $client->save();
        return new ClientResource($client);
    }

    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return new ClientResource($client);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json();
    }

    public function addToPanier(Client $client, Request $request)
    {
        // Valider la requête
        $data = $request->validate([
            'atelier_id' => ['required', 'string'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $atelierId = $data['atelier_id'];
        $quantity = $data['quantity'] ?? 1;

        $atelier = Atelier::find($atelierId);
        if (! $atelier) {
            return response()->json(['message' => 'Atelier introuvable.'], 404);
        }

        $panier = $client->panier ?? ['ateliers' => []];

        if (! isset($panier['ateliers'])) {
            $panier['ateliers'] = [];
        }

        $found = false;
        foreach ($panier['ateliers'] as &$item) {
            if (data_get($item, 'id') == $atelierId) {
                $item['quantity'] = max(1, ($item['quantity'] ?? 0) + $quantity);
                $found = true;
                break;
            }
        }
        unset($item);

        if (! $found) {
            $panier['ateliers'][] = [
                'id' => $atelierId,
                'quantity' => $quantity,
                'nom' => $atelier->nom ?? null,
                'prix' => $atelier->prix ?? null,
            ];
        }

        $client->panier = $panier;
        $client->save();

        return response()->json([
            'message' => 'Atelier ajouté au panier avec succès.',
            'panier' => $panier,
        ], 201);
    }

    public function removeFromPanier(Client $client, Request $request)
    {
        $data = $request->validate([
            'atelier_id' => ['required', 'string'],
        ]);

        $atelierId = $data['atelier_id'];

        $panier = $client->panier ?? ['ateliers' => []];

        if (!isset($panier['ateliers'])) {
            $panier['ateliers'] = [];
        }

        $panier['ateliers'] = array_filter($panier['ateliers'], function ($item) use ($atelierId) {
            return data_get($item, 'id') != $atelierId;
        });

        $client->panier = $panier;
        $client->save();

        return response()->json([
            'message' => 'Atelier retiré du panier.',
            'panier' => $panier,
        ], 200);
    }

    public function emptyPanier(Client $client)
    {
        $client->panier = ['ateliers' => []];
        $client->save();

        return response()->json([
            'message' => 'Panier vidé.',
            'panier' => $client->panier,
        ], 200);
    }

    public function processPanier(Client $client, Request $request)
    {
        $data = $request->validate([
            'numCarte' => ['required', 'string'],
            'methode_paiement' => ['nullable', 'string', 'in:carte,virement,paypal,credit_fidelite'],

        ]);

        $panier = $client->panier;

        if (empty($panier['ateliers'])) {
            return response()->json(['message' => 'Le panier est vide.'], 400);
        }




        // Vérifier la capacité pour tous les ateliers
        foreach ($panier['ateliers'] as $item) {
            $atelier = Atelier::find($item['id']);
            if (!$atelier) {
                return response()->json(['message' => 'Atelier introuvable: ' . $item['id']], 404);
            }

            if ($atelier->remainingCapacity() < $item['quantity']) {
                return response()->json([
                    'message' => 'Capacité insuffisante pour l\'atelier: ' . $atelier->nom,
                    'remaining' => $atelier->remainingCapacity()
                ], 422);
            }
        }

        // Créer les réservations
        $reservations = [];
        foreach ($panier['ateliers'] as $item) {
            $atelier = Atelier::find($item['id']);
            $prix = ($atelier->prix ?? 0) * $item['quantity'];
            if($atelier->vip && $request['methode_paiement']=='credit_fidelite'){
                $prix = 0;
                $client->credit_fidelite -=10;
            }

            // Créer la réservation embedded dans l'atelier
            $reservation = $atelier->reservations()->create([
                'nbPersonne' => $item['quantity'],
                'prix' => $prix,
                'client_id' => $client->id,
            ]);

            // Créer le paiement embedded dans la réservation
            $reservation->paiements()->create([
                'numCarte' => $data['numCarte'],
                'montant' => $prix,
                'methode_paiement' => $data['methode_paiement'] ?? "carte",
                'statut' => 'paye',
                'payement_recieved_at' => now(),
            ]);

            if( $request['methode_paiement']!='credit_fidelite'){
                $client->credit_fidelite += $item['quantity'] ;
            }

            $reservations[] = $reservation;
        }


        // Vider le panier
        $client->panier = ['ateliers' => []];


        $client->save();

        return response()->json([
            'message' => 'Panier traité avec succès. Réservations créées.',
            'reservations' => $reservations,
        ], 201);
    }
}

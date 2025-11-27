<?php

// ============================================
// App/Http/Controllers/ClientController.php
// ============================================

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Atelier;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class ClientController extends Controller
{
    public function index()
    {
        return ClientResource::collection(Client::all());
    }

    public function store(ClientRequest $request)
    {
        $data = $request->validated();
        $data['credit_fidelite'] = 0;

        $client = Client::create($data);

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
        return response()->json(['message' => 'Client supprimé avec succès.']);
    }

    public function addToPanier(Client $client, Request $request)
    {
        $data = $request->validate([
            'atelier_id' => ['required', 'string'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $atelierId = $data['atelier_id'];
        $quantity = $data['quantity'] ?? 1;

        $atelier = Atelier::find($atelierId);
        if (!$atelier) {
            return response()->json(['message' => 'Atelier introuvable.'], 404);
        }

        $panier = $client->panier ?? ['ateliers' => []];

        if (!isset($panier['ateliers'])) {
            $panier['ateliers'] = [];
        }

        // Vérifier si l'atelier existe déjà dans le panier
        $found = false;
        foreach ($panier['ateliers'] as &$item) {
            // Comparer les IDs (string ou ObjectId)
            $itemId = is_object($item['id']) ? (string) $item['id'] : $item['id'];
            if ($itemId == $atelierId) {
                $item['quantity'] = max(1, ($item['quantity'] ?? 0) + $quantity);
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $panier['ateliers'][] = [
                'id' => $atelierId,
                'quantity' => $quantity,
                'nom' => $atelier->nom,
                'prix' => $atelier->prix,
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

        $panier['ateliers'] = array_values(array_filter($panier['ateliers'], function ($item) use ($atelierId) {
            $itemId = is_object($item['id']) ? (string) $item['id'] : $item['id'];
            return $itemId != $atelierId;
        }));

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
            $methodePaiement = $data['methode_paiement'] ?? 'carte';

            // Gestion des ateliers VIP
            if ($atelier->vip && $methodePaiement == 'credit_fidelite') {
                if ($client->credit_fidelite < 10) {
                    return response()->json([
                        'message' => 'Crédits de fidélité insuffisants. Requis: 10, Disponible: ' . $client->credit_fidelite,
                    ], 422);
                }
                $prix = 0;
                $client->credit_fidelite -= 10;
            } elseif ($atelier->vip && $methodePaiement != 'credit_fidelite') {
                return response()->json([
                    'message' => 'Les ateliers VIP ne peuvent être payés qu\'avec des crédits de fidélité. Atelier: ' . $atelier->nom,
                ], 422);
            } elseif (!$atelier->vip && $methodePaiement == 'credit_fidelite') {
                return response()->json([
                    'message' => 'Les crédits de fidélité ne sont utilisables que pour les ateliers VIP. Votre solde: ' . $client->credit_fidelite,
                ], 422);
            }

            // Créer la réservation avec ObjectId
            $reservation = $atelier->reservations()->create([
                'nbPersonne' => $item['quantity'],
                'prix' => $prix,
                'client_id' => new ObjectId((string) $client->_id),
                'atelier_id' => new ObjectId((string) $atelier->_id),
            ]);

            // Créer le paiement
            $reservation->paiement()->create([
                'numCarte' => $data['numCarte'],
                'montant' => $prix,
                'methode_paiement' => $methodePaiement,
                'statut' => 'validé',
                'payement_recieved_at' => now(),
                'reservation_id' => new ObjectId((string) $reservation->_id),
            ]);

            // Ajouter des crédits de fidélité (sauf si payé avec crédits)
            if ($methodePaiement != 'credit_fidelite') {
                $client->credit_fidelite += $item['quantity'];
            }

            $reservations[] = $reservation;
        }

        // Vider le panier
        $client->panier = ['ateliers' => []];
        $client->save();

        return response()->json([
            'message' => 'Panier traité avec succès. Réservations créées.',
            'reservations' => $reservations,
            'credit_fidelite' => $client->credit_fidelite,
        ], 201);
    }
}

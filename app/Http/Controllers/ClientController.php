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
        $methodePaiement = $request->input('methode_paiement', 'carte');

        // Validation conditionnelle selon la méthode de paiement
        $rules = [
            'methode_paiement' => ['required', 'string', 'in:carte,virement,paypal,credit_fidelite'],
        ];

        if ($methodePaiement === 'carte') {
            $rules['numCarte'] = ['required', 'string', 'min:13', 'max:19']; // Numéro de carte bancaire
        } elseif ($methodePaiement === 'virement') {
            $rules['numCarte'] = ['required', 'string', 'min:15']; // IBAN
        }
        // Pas de validation pour PayPal et credit_fidelite

        $data = $request->validate($rules);

        $panier = $client->panier;

        if (empty($panier['ateliers'])) {
            return response()->json(['message' => 'Le panier est vide.'], 400);
        }

        // Vérifier la compatibilité méthode de paiement / ateliers
        $hasVip = false;
        $hasNonVip = false;

        foreach ($panier['ateliers'] as $item) {
            $atelier = Atelier::find($item['id']);
            if (!$atelier) {
                return response()->json(['message' => 'Atelier introuvable: ' . ($item['nom'] ?? 'Inconnu')], 404);
            }

            if ($atelier->vip) {
                $hasVip = true;
            } else {
                $hasNonVip = true;
            }
        }

        // Validation des règles de paiement VIP
        if ($methodePaiement == 'credit_fidelite' && $hasNonVip) {
            return response()->json([
                'message' => 'Les crédits de fidélité ne peuvent être utilisés que pour les ateliers VIP. Veuillez retirer les ateliers non-VIP de votre panier.'
            ], 422);
        }

        if ($hasVip && $methodePaiement != 'credit_fidelite') {
            return response()->json([
                'message' => 'Les ateliers VIP ne peuvent être payés qu\'avec des crédits de fidélité. Veuillez choisir \'Crédit de fidélité\' comme méthode de paiement.'
            ], 422);
        }

        // Vérifier les crédits disponibles si paiement par crédit
        if ($methodePaiement == 'credit_fidelite') {
            $totalCreditsNeeded = 0;
            foreach ($panier['ateliers'] as $item) {
                $totalCreditsNeeded += $item['quantity'] * 10; // 10 crédits par personne
            }

            if ($client->credit_fidelite < $totalCreditsNeeded) {
                return response()->json([
                    'message' => "Crédits de fidélité insuffisants. Requis: {$totalCreditsNeeded}, Disponible: {$client->credit_fidelite}"
                ], 422);
            }
        }

        // Vérifier la capacité pour tous les ateliers
        foreach ($panier['ateliers'] as $item) {
            $atelier = Atelier::find($item['id']);

            if (!$atelier) {
                return response()->json(['message' => 'Atelier introuvable: ' . ($item['nom'] ?? 'Inconnu')], 404);
            }

            $remaining = $atelier->remainingCapacity();
            if ($remaining < $item['quantity']) {
                return response()->json([
                    'message' => "Capacité insuffisante pour l'atelier {$atelier->nom}. Places restantes: {$remaining}"
                ], 422);
            }
        }

        // Créer les réservations
        $reservations = [];
        $totalCreditsUsed = 0;

        foreach ($panier['ateliers'] as $item) {
            $atelier = Atelier::find($item['id']);
            $prix = ($atelier->prix ?? 0) * $item['quantity'];

            // Gestion des paiements VIP par crédit
            if ($atelier->vip && $methodePaiement == 'credit_fidelite') {
                $creditsNeeded = $item['quantity'] * 10;
                $totalCreditsUsed += $creditsNeeded;
                $prix = 0; // Gratuit quand payé avec crédits
            }

            // Gérer le numéro de carte/identifiant selon la méthode de paiement
            $identifiantPaiement = '';
            switch ($methodePaiement) {
                case 'credit_fidelite':
                    $identifiantPaiement = 'CREDIT_FIDELITE';
                    break;
                case 'carte':
                    // Masquer le numéro de carte (garder les 4 derniers chiffres)
                    $numCarte = $data['numCarte'] ?? '';
                    $identifiantPaiement = '****' . substr($numCarte, -4);
                    break;
                case 'virement':
                    // Masquer l'IBAN (garder les 4 derniers caractères)
                    $numCarte = $data['numCarte'] ?? '';
                    $identifiantPaiement = 'IBAN ****' . substr(str_replace(' ', '', $numCarte), -4);
                    break;
                case 'paypal':
                    // Générer un ID de transaction PayPal simulé
                    $identifiantPaiement = 'PP-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
                    break;
                default:
                    $identifiantPaiement = $data['numCarte'] ?? '';
            }

            // Créer les paiements embarqués avec ObjectId
            $paiementId = new ObjectId();
            $paiements = [];
            $paiements[] = [
                '_id' => $paiementId,
                'numCarte' => $identifiantPaiement,
                'montant' => $prix,
                'methode_paiement' => $methodePaiement,
                'statut' => 'validé',
                'payement_recieved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Créer la réservation avec paiements embarqués et ObjectId
            $reservationId = new ObjectId();
            $reservationData = [
                '_id' => $reservationId,
                'nbPersonne' => $item['quantity'],
                'prix' => $prix,
                'client_id' => new ObjectId((string) $client->_id),
                'paiements' => $paiements,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $atelier->push('reservations', $reservationData);

            $reservations[] = $reservationData;
        }

        // Gérer les crédits de fidélité
        if ($methodePaiement == 'credit_fidelite') {
            // Déduire les crédits utilisés
            $client->credit_fidelite -= $totalCreditsUsed;
        } else {
            // Ajouter des crédits pour les achats non-VIP (1 crédit par personne)
            foreach ($panier['ateliers'] as $item) {
                $client->credit_fidelite += $item['quantity'];
            }
        }

        // Vider le panier
        $client->panier = ['ateliers' => []];
        $client->save();

        $totalReservations = count($reservations);
        $message = "Paiement effectué avec succès! {$totalReservations} réservation(s) créée(s).";

        if ($methodePaiement == 'credit_fidelite') {
            $message .= " {$totalCreditsUsed} crédits utilisés. Solde restant: {$client->credit_fidelite}";
        } else {
            $message .= " Crédits de fidélité gagnés: +{$totalReservations}. Nouveau solde: {$client->credit_fidelite}";
        }

        return response()->json([
            'message' => $message,
            'reservations' => $reservations,
            'credit_fidelite' => $client->credit_fidelite,
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Atelier;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return ClientResource::collection(Client::all());
    }

    public function store(ClientRequest $request)
    {
        return new ClientResource(Client::create($request->validated()));
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

        // Persist panier on client and save
        $client->panier = $panier;
        $client->save();

        return response()->json([
            'message' => 'Atelier ajouté au panier avec succès.',
            'panier' => $panier,
        ], 201);
    }
}

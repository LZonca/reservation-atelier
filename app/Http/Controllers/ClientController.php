<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;

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
}

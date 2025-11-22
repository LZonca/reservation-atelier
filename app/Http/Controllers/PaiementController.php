<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaiementRequest;
use App\Http\Resources\PaiementResource;
use App\Models\Paiement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaiementController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Paiement::class);

        return PaiementResource::collection(Paiement::all());
    }

    public function store(PaiementRequest $request)
    {
        $this->authorize('create', Paiement::class);

        return new PaiementResource(Paiement::create($request->validated()));
    }

    public function show(Paiement $paiement)
    {
        $this->authorize('view', $paiement);

        return new PaiementResource($paiement);
    }

    public function update(PaiementRequest $request, Paiement $paiement)
    {
        $this->authorize('update', $paiement);

        $paiement->update($request->validated());

        return new PaiementResource($paiement);
    }

    public function destroy(Paiement $paiement)
    {
        $this->authorize('delete', $paiement);

        $paiement->delete();

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PanierRequest;
use App\Http\Resources\PanierResource;
use App\Models\Panier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Panier::class);

        return PanierResource::collection(Panier::all());
    }

    public function store(PanierRequest $request)
    {
        $this->authorize('create', Panier::class);

        return new PanierResource(Panier::create($request->validated()));
    }

    public function show(Panier $panier)
    {
        $this->authorize('view', $panier);

        return new PanierResource($panier);
    }

    public function update(PanierRequest $request, Panier $panier)
    {
        $this->authorize('update', $panier);

        $panier->update($request->validated());

        return new PanierResource($panier);
    }

    public function destroy(Panier $panier)
    {
        $this->authorize('delete', $panier);

        $panier->delete();

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalleRequest;
use App\Http\Resources\SalleResource;
use App\Models\Salle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SalleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Salle::class);

        return SalleResource::collection(Salle::all());
    }

    public function store(SalleRequest $request)
    {
        $this->authorize('create', Salle::class);

        return new SalleResource(Salle::create($request->validated()));
    }

    public function show(Salle $salle)
    {
        $this->authorize('view', $salle);
        $salle= Salle::with(['boutique'])->get();
        return new SalleResource($salle);
    }

    public function update(SalleRequest $request, Salle $salle)
    {
        $this->authorize('update', $salle);

        $salle->update($request->validated());

        return new SalleResource($salle);
    }

    public function destroy(Salle $salle)
    {
        $this->authorize('delete', $salle);

        $salle->delete();

        return response()->json();
    }
}

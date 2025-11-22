<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntervenantRequest;
use App\Http\Resources\IntervenantResource;
use App\Models\Intervenant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IntervenantController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Intervenant::class);

        return IntervenantResource::collection(Intervenant::all());
    }

    public function store(IntervenantRequest $request)
    {
        $this->authorize('create', Intervenant::class);

        return new IntervenantResource(Intervenant::create($request->validated()));
    }

    public function show(Intervenant $intervenant)
    {
        $this->authorize('view', $intervenant);

        return new IntervenantResource($intervenant);
    }

    public function update(IntervenantRequest $request, Intervenant $intervenant)
    {
        $this->authorize('update', $intervenant);

        $intervenant->update($request->validated());

        return new IntervenantResource($intervenant);
    }

    public function destroy(Intervenant $intervenant)
    {
        $this->authorize('delete', $intervenant);

        $intervenant->delete();

        return response()->json();
    }
}

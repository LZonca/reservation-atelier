<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalleRequest;
use App\Http\Resources\SalleResource;
use App\Models\Salle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use MongoDB\BSON\ObjectId;

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

        $data = $request->validated();

        if (isset($data['boutique_id']) && $data['boutique_id'] !== '') {
            try {
                $data['boutique_id'] = new ObjectId($data['boutique_id']);
            } catch (\Exception $e) {
                unset($data['boutique_id']);
            }
        }

        return new SalleResource(Salle::create($data));
    }

    public function show(Salle $salle)
    {
        $this->authorize('view', $salle);
        $salle = $salle->load(['boutique']);
        return new SalleResource($salle);
    }

    public function update(SalleRequest $request, Salle $salle)
    {
        $this->authorize('update', $salle);

        $data = $request->validated();

        if (isset($data['boutique_id']) && $data['boutique_id'] !== '') {
            try {
                $data['boutique_id'] = new ObjectId($data['boutique_id']);
            } catch (\Exception $e) {
                unset($data['boutique_id']);
            }
        }

        $salle->update($data);

        return new SalleResource($salle);
    }

    public function destroy(Salle $salle)
    {
        $this->authorize('delete', $salle);

        $salle->delete();

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\InfoContactResource;
use App\Models\InfoContact;
use Illuminate\Http\Request;

class InfoContactController extends Controller
{
    public function index()
    {
        return InfoContactResource::collection(InfoContact::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mail' => ['required'],
            'phone' => ['required'],
            'twitter' => ['nullable'],
            'instagram' => ['nullable'],
            'youtube' => ['nullable'],
            'pinterest' => ['nullable'],
            'bluesky' => ['nullable'],
            'website' => ['nullable'],
        ]);

        return new InfoContactResource(InfoContact::create($data));
    }

    public function show(InfoContact $infoContact)
    {
        return new InfoContactResource($infoContact);
    }

    public function update(Request $request, InfoContact $infoContact)
    {
        $data = $request->validate([
            'mail' => ['required'],
            'phone' => ['required'],
            'twitter' => ['nullable'],
            'instagram' => ['nullable'],
            'youtube' => ['nullable'],
            'pinterest' => ['nullable'],
            'bluesky' => ['nullable'],
            'website' => ['nullable'],
        ]);

        $infoContact->update($data);

        return new InfoContactResource($infoContact);
    }

    public function destroy(InfoContact $infoContact)
    {
        $infoContact->delete();

        return response()->json();
    }
}

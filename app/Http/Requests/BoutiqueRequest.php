<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoutiqueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'adresse.rue' => ['nullable', 'string', 'max:255'],
            'adresse.numero' => ['nullable', 'string', 'max:50'],
            'adresse.ville' => ['nullable', 'string', 'max:255'],
            'adresse.code_postal' => ['nullable', 'string', 'max:20'],
            'infoContact.email' => ['nullable', 'email', 'max:255'],
            'infoContact.telephone' => ['nullable', 'string', 'max:20'],
            'infoContact.website' => ['nullable', 'url', 'max:255'],
            'infoContact.youtube' => ['nullable', 'url', 'max:255'],
            'infoContact.instagram' => ['nullable', 'url', 'max:255'],
            'infoContact.facebook' => ['nullable', 'url', 'max:255'],
            'infoContact.twitter' => ['nullable', 'url', 'max:255'],
            'infoContact.pinterest' => ['nullable', 'url', 'max:255'],
            'infoContact.bluesky' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

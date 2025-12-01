<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtelierRequest extends FormRequest
{
    public function rules(): array
    {
        // si c'est une création (POST) -> required, sinon (PUT/PATCH) -> sometimes (accept partial updates)
        $isCreate = $this->isMethod('POST');
        $req = $isCreate ? 'required' : 'sometimes';

        return [
            'nom' => [$req, 'string', 'max:255'],
            'description' => ['nullable'],
            'duree' => [$req, 'integer'],
            'prix' => [$req, 'numeric'],
            'date' => [$req, 'date'],
            'vip' => [$req, 'boolean'],
            'salle_id' => [$req],
            'employe_id' => [$req],

            // Validation pour l'intervenant embedded
            'intervenant' => ['nullable', 'array'],
            'intervenant.nom' => ['nullable', 'string', 'max:255'],
            'intervenant.prenom' => ['nullable', 'string', 'max:255'],

            // Validation pour infoContact de l'intervenant
            'intervenant.infoContact' => ['nullable', 'array'],
            'intervenant.infoContact.email' => ['nullable', 'email', 'max:255'],
            'intervenant.infoContact.telephone' => ['nullable', 'string', 'max:20'],
            'intervenant.infoContact.website' => ['nullable', 'url', 'max:255'],
            'intervenant.infoContact.youtube' => ['nullable', 'url', 'max:255'],
            'intervenant.infoContact.instagram' => ['nullable', 'url', 'max:255'],
            'intervenant.infoContact.facebook' => ['nullable', 'url', 'max:255'],
            'intervenant.infoContact.twitter' => ['nullable', 'url', 'max:255'],
            'intervenant.infoContact.pinterest' => ['nullable', 'url', 'max:255'],
            'intervenant.infoContact.bluesky' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

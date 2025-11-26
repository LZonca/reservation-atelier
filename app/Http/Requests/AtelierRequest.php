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
            'intervenant.nom' => ['required_with:intervenant', 'string'],
            'intervenant.prenom' => ['required_with:intervenant', 'string'],
            'intervenant.email' => ['required_with:intervenant', 'email'],
            'intervenant.telephone' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

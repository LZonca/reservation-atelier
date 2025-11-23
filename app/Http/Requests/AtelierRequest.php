<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtelierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required'],
            'description' => ['nullable'],
            'duree' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'salle_id' => ['required'],
            'employe_id' => ['required'],

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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'numCarte' => ['required'],
            'montant' => ['required', 'numeric'],
            'statut' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IntervenantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required'],
            'prenom' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'telephone' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

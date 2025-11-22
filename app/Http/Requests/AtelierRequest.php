<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtelierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required'],
            'date' => ['required', 'date'],
            'intervenant_id' => ['required', 'exists:intervenants,id'],
            'salle_id' => ['required', 'exists:salles,id'],
            'employe_id' => ['required', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

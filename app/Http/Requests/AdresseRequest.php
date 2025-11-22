<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdresseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rue' => ['required'],
            'numero' => ['required', 'integer'],
            'ville' => ['required'],
            'code_postal' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}

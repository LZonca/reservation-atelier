<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'capacite' => ['required', 'integer'],
            'categorie' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

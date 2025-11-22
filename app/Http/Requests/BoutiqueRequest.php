<?php

namespace App\Http\Requests;

class BoutiqueRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

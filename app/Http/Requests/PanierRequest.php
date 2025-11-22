<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PanierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'expires_at' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

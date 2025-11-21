<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'prix' => ['required', 'numeric'],
            'nbPersonne' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

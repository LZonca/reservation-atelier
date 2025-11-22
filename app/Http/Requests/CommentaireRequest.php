<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentaireRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'commentaire' => ['required', 'string', 'max:500'],
            'client_id'   => ['required'],
            'note'        => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

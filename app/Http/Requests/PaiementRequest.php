<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
{
    public function rules(): array
    {
        $methodePaiement = $this->input('methode_paiement', 'carte');

        $rules = [
            'methode_paiement' => ['required', 'string', 'in:carte,cheque,espece,credit_fidelite,paypal'],
            'montant' => ['required', 'numeric'],
            'statut' => ['required'],
        ];

        // Le numéro de carte n'est requis que pour les paiements par carte
        if ($methodePaiement === 'carte') {
            $rules['numCarte'] = ['required', 'string', 'min:13', 'max:19'];
        } elseif ($methodePaiement === 'paypal') {
            // Pour PayPal, on peut stocker l'ID de transaction ou l'email
            $rules['numCarte'] = ['nullable', 'string', 'max:255'];
        } elseif ($methodePaiement === 'cheque') {
            $rules['numCarte'] = ['required', 'string', 'min:13', 'max:19'];
        }
        // Pour cheque, espece, credit_fidelite : pas de numéro de carte requis

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}

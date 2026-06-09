<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom_client' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'sites_relais' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'statut' => 'required|in:actif,inactif,suspendu',
            'categorie' => 'nullable|string|max:255',
            'jour_reabonnement' => 'required|integer|min:1|max:31',
            'montant' => 'required|numeric|min:0',
            'a_paye' => 'required|in:0,1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom_client.required' => 'Le nom du client est obligatoire.',
            'contact.required' => 'Le contact est obligatoire.',
            'jour_reabonnement.required' => 'Le jour de réabonnement est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
        ];
    }
}

<?php

namespace App\Http\Requests\Commande;

use Illuminate\Foundation\Http\FormRequest;

class CommandeRequest extends FormRequest
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
            'type' => 'string',
            'adress' => 'string',
            'examen_ids.*' => 'exists:examens,id',
            'type_bilan_ids.*' => 'exists:type_bilans,id',
            'client_id' => 'exists:users,id',
            "date_prelevement" => "string",
            'montant' => 'required',
            'numero' => 'nullable',
            'payed' => 'boolean|in:0,1',
        ];
    }
}

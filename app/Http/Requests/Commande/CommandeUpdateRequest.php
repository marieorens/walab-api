<?php

namespace App\Http\Requests\Commande;

use Illuminate\Foundation\Http\FormRequest;

class CommandeUpdateRequest extends FormRequest
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
            'id' => 'exists:commandes,id',
            'type' => 'required',
            'adress' => 'required',
            'examen_id' => 'exists:examens,id',
            'type_bilan_id' => 'exists:type_bilans,id',
            'date_prelevement' => 'string'

        ];
    }
}

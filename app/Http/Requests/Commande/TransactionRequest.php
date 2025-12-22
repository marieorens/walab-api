<?php

namespace App\Http\Requests\Commande;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'status' => 'required',
            'transaction_id' => "string",
            'reference' => "string",
            'mode' => "string",

        ];
    }

     /**
     * Mapper la réponse API à la requête.
     */
    public function mapFromApiResponse(array $transactionData)
    {
        $data = $transactionData['custom_metadata'];
        $this->merge([
            'adress' => $data['adress'],
            'examen_ids' => $data['examen_ids'] ?? null,
            'type_bilan_ids' => $data['type_bilan_ids']?? null,
            'client_id' => $data['client_id'],
            "date_prelevement" => $data['date_prelevement'],
            'montant' => $transactionData['amount'],
            'status' => $transactionData['status'],
            'transaction_id' => $transactionData['id'],
            'reference' => $transactionData['reference'],
            'type' => $transactionData['description'],
            'mode' => $transactionData['mode'],
        ]);
    }
}

<?php

namespace App\Http\Requests\Commande;

use Illuminate\Foundation\Http\FormRequest;

class ChatCommandeRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            // Le contenu est requis SEULEMENT si on n'envoie pas d'audio
            'content' => 'required_without:audio',
            'code' => 'required|exists:App\Models\Commande,code',
            // Validation du fichier audio (mp3, wav, webm, etc.)
            'audio' => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav,webm,m4a|max:10240', // Max 10Mo
        ];
    }
}

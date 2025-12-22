<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $rules = [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'gender' => ['required', 'string'],
            // 'country' => ['required', 'string', 'max:255'],
            'city' => ['string', 'max:255'],
            'date_naissance' => ['required', 'string', 'max:255'],
            'adress' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'url_profil' => ['nullable', 'file'],
            'role_id' => ['integer', 'exists:roles,id'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,blocked'],
            'user_type' => ['nullable', 'string', 'in:client,professionnel'],
        ];

        // Si c'est un professionnel, ajouter les règles spécifiques
        if ($this->user_type === 'professionnel') {
            $rules['order_number'] = ['required', 'string', 'max:255', 'unique:practitioners,order_number'];
            $rules['profession'] = ['required', 'string'];
            $rules['other_profession'] = ['required_if:profession,other', 'string', 'max:100'];
            $rules['certificate'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']; // 5MB max
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Le prénom est obligatoire.',
            'firstname.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstname.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'lastname.required' => 'Le nom de famille est obligatoire.',
            'lastname.string' => 'Le nom de famille doit être une chaîne de caractères.',
            'lastname.max' => 'Le nom de famille ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse e-mail doit suivre le bon format',
            'email.unique' => 'L\'adresse e-mail a déjà été prise.',
            'gender.required' => 'Le sexe est obligatoire.',
            'gender.string' => 'Le sexe doit être une chaîne de caractères.',
            'gender.in' => 'Le sexe doit être soit "male" soit "female".',
            'country.required' => 'Le pays est obligatoire.',
            'country.string' => 'Le pays doit être une chaîne de caractères.',
            'country.max' => 'Le pays ne peut pas dépasser 255 caractères.',
            'city.required' => 'La ville est obligatoire.',
            'city.string' => 'La ville doit être une chaîne de caractères.',
            'city.max' => 'La ville ne peut pas dépasser 255 caractères.',
            'district.required' => 'Le quartier est obligatoire.',
            'district.string' => 'Le quartier doit être une chaîne de caractères.',
            'district.max' => 'Le quartier ne peut pas dépasser 255 caractères.',
            'adress.required' => 'L\'adresse est obligatoire.',
            'adress.string' => 'L\'adresse doit être une chaîne de caractères.',
            'adress.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 15 caractères.',
            'url_profil.string' => 'L\'URL du profil doit être une chaîne de caractères.',
            'url_profil.max' => 'L\'URL du profil ne peut pas dépasser 255 caractères.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.integer' => 'L\'ID du rôle doit être un entier.',
            'role_id.exists' => 'Le rôle spécifié n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères',
            'password.max' => 'Le mot de passe ne peut pas dépasser 255 caractères.',
            'status.string' => 'Le statut doit être une chaîne de caractères.',
            'status.in' => 'Le statut doit être soit "active" soit "blocked".',
            'user_type.string' => 'Le type d\'utilisateur doit être une chaîne de caractères.',
            'user_type.in' => 'Le type d\'utilisateur doit être soit "client" soit "professionnel".',
            'order_number.required' => 'Le numéro d\'ordre est obligatoire pour les professionnels.',
            'order_number.string' => 'Le numéro d\'ordre doit être une chaîne de caractères.',
            'order_number.max' => 'Le numéro d\'ordre ne peut pas dépasser 255 caractères.',
            'order_number.unique' => 'Ce numéro d\'ordre est déjà utilisé.',
            'profession.required' => 'La profession est obligatoire pour les professionnels.',
            'profession.string' => 'La profession doit être une chaîne de caractères.',
            'profession.in' => 'La profession sélectionnée n\'est pas valide.',
        ];
    }
}

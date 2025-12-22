<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUPRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'unique:users,email'],
            'gender' => ['string', 'in:male,female'],
            // 'country' => ['required', 'string', 'max:255'],
            'city' => ['string', 'max:255'],
            'date_naissance' => ['required', 'string', 'max:255'],
            'adress' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'url_profil' => ['nullable', 'file'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'status' => ['nullable', 'string', 'in:active,blocked'],
        ];
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
        ];
    }
}

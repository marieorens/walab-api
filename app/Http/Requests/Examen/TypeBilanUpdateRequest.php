<?php

namespace App\Http\Requests\Examen;

use Illuminate\Foundation\Http\FormRequest;

class TypeBilanUpdateRequest extends FormRequest
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
            'id' => ['exists:type_bilans,id'],
            'label' => ['required'],
            // 'laboratorie_id' => 'exists:laboratories,id',
            // 'icon' => ['file'],
            'price' => ['required'],
            'description' => ['required'],
        ];
    }
}

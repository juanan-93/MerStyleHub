<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignQuestionnaireRequest extends FormRequest
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
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_ids.required' => 'Debes seleccionar al menos un usuario.',
            'user_ids.min' => 'Debes seleccionar al menos un usuario.',
            'user_ids.*.exists' => 'Uno de los usuarios seleccionados no existe.',
        ];
    }
}

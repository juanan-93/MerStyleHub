<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionnaireRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:test,text,select,file,info',
            'questions.*.required' => 'nullable|boolean',
            'questions.*.allow_other_option' => 'nullable|boolean',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*' => 'nullable|string|max:255',
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
            'title.required' => 'El título del cuestionario es obligatorio.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser activo o inactivo.',
            'questions.required' => 'Debes añadir al menos una pregunta.',
            'questions.min' => 'Debes añadir al menos una pregunta.',
            'questions.*.text.required' => 'El texto de la pregunta es obligatorio.',
            'questions.*.type.required' => 'El tipo de pregunta es obligatorio.',
            'questions.*.type.in' => 'El tipo de pregunta no es válido.',
            'questions.*.options.required_if' => 'Las preguntas de tipo test y select requieren al menos 2 opciones.',
            'questions.*.options.min' => 'Debes añadir al menos 2 opciones.',
            'questions.*.options.*.required' => 'El texto de la opción es obligatorio.',
        ];
    }
}

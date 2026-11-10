<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'min:3', 'max:255'],
            'author'         => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'published_year' => ['required', 'integer', 'between:1000,' . date('Y')],
            'category_id'    => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'          => 'El título es obligatorio.',
            'title.min'               => 'El título debe tener al menos 3 caracteres.',
            'author.required'         => 'El autor es obligatorio.',
            'published_year.required' => 'El año de publicación es obligatorio.',
            'published_year.between'  => 'El año debe estar entre 1000 y ' . date('Y') . '.',
            'category_id.required'    => 'La categoría es obligatoria.',
            'category_id.exists'      => 'La categoría seleccionada no existe.',
        ];
    }
}

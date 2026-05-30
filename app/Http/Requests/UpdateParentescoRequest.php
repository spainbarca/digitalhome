<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParentescoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'         => ['required', 'string', 'max:80', Rule::unique('parentescos', 'nombre')->ignore($this->parentesco)],
            'nombre_inverso' => ['nullable', 'string', 'max:80'],
            'grupo'          => ['nullable', 'string', 'max:60'],
            'activo'         => ['boolean'],
        ];
    }
}

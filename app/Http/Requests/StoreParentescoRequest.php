<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParentescoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'         => ['required', 'string', 'max:80', 'unique:parentescos,nombre'],
            'nombre_inverso' => ['nullable', 'string', 'max:80'],
            'grupo'          => ['nullable', 'string', 'max:60'],
            'activo'         => ['boolean'],
        ];
    }
}

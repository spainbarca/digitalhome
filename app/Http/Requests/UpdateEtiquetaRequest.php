<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEtiquetaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:60'],
            'color'  => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icono'  => ['nullable', 'string', 'max:80'],
        ];
    }

    public function attributes(): array
    {
        return ['nombre' => 'nombre', 'color' => 'color'];
    }
}

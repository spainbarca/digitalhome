<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoInmuebleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80', Rule::unique('tipo_inmueble', 'nombre')->ignore($this->tipo)],
            'icono'  => ['nullable', 'string', 'max:60'],
            'activo' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['nombre' => 'nombre', 'icono' => 'ícono'];
    }
}

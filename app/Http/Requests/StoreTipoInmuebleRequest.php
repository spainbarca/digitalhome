<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoInmuebleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80', 'unique:tipo_inmueble,nombre'],
            'icono'  => ['nullable', 'string', 'max:60'],
            'activo' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['nombre' => 'nombre', 'icono' => 'ícono'];
    }
}

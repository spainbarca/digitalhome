<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstadoEmpleoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $estadoEmpleo = $this->route('estadoEmpleo');

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('estado_empleo', 'nombre')->ignore($estadoEmpleo)],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string'],
            'color'       => ['nullable', 'string'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'icono'       => 'ícono',
            'color'       => 'color',
            'activo'      => 'estado',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstadoMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $estado = $this->route('estado');

        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('estados_matricula', 'nombre')->ignore($estado)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'icono'       => ['nullable', 'string', 'max:100'],
            'color'       => ['required', 'string', 'in:green,red,blue,orange,purple,grey'],
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

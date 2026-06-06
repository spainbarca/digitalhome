<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTurnoEducativoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $turno = $this->route('turno');

        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('turnos_educativos', 'nombre')->ignore($turno)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'icono'       => ['nullable', 'string', 'max:100'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'icono'       => 'ícono',
            'activo'      => 'estado',
        ];
    }
}

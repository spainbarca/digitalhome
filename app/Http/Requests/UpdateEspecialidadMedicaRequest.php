<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEspecialidadMedicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $especialidad = $this->route('especialidadMedica');

        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('especialidades_medicas', 'nombre')->ignore($especialidad)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'icono'       => ['nullable', 'string', 'max:100'],
            'imagen_path' => ['nullable', 'string', 'max:500'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'icono'       => 'ícono',
            'imagen_path' => 'imagen',
            'activo'      => 'estado',
        ];
    }
}

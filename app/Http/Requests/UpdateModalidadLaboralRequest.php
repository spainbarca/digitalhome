<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModalidadLaboralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $modalidadLaboral = $this->route('modalidadLaboral');

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('modalidad_laboral', 'nombre')->ignore($modalidadLaboral)],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string'],
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

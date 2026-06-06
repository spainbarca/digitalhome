<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoInstitucionEducativaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:100', 'unique:tipo_institucion_educativa,nombre'],
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

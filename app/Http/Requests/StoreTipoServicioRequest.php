<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:100', 'unique:tipo_servicio,nombre'],
            'icono'            => ['nullable', 'string', 'max:60'],
            'unidad_medida_id' => ['required', 'exists:unidad_medida,id'],
            'activo'           => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'           => 'nombre',
            'icono'            => 'ícono',
            'unidad_medida_id' => 'unidad de medida',
            'activo'           => 'estado',
        ];
    }
}

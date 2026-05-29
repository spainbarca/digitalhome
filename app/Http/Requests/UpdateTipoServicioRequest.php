<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipo = $this->route('tipo');

        return [
            'nombre'           => ['required', 'string', 'max:100', Rule::unique('tipo_servicio', 'nombre')->ignore($tipo)],
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

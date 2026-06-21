<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoOperadorViajeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $tipoOperadorViaje = $this->route('tipoOperadorViaje');
        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_operador_viaje', 'nombre')->ignore($tipoOperadorViaje)],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['nombre' => 'nombre', 'descripcion' => 'descripción', 'icono' => 'ícono', 'activo' => 'estado'];
    }
}

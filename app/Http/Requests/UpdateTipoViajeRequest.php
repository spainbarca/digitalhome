<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoViajeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $tipoViaje = $this->route('tipoViaje');
        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_viaje', 'nombre')->ignore($tipoViaje)],
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

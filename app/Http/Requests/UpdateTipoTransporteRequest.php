<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoTransporteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $tipoTransporte = $this->route('tipoTransporte');
        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_transporte', 'nombre')->ignore($tipoTransporte)],
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

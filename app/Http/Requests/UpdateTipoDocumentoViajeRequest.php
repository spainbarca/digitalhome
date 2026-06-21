<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoDocumentoViajeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $tipoDocumentoViaje = $this->route('tipoDocumentoViaje');
        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_documento_viaje', 'nombre')->ignore($tipoDocumentoViaje)],
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

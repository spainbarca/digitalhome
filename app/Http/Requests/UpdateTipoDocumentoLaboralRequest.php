<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoDocumentoLaboralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoDocumentoLaboral = $this->route('tipoDocumentoLaboral');

        return [
            'nombre'               => ['required', 'string', 'max:255', Rule::unique('tipo_documento_laboral', 'nombre')->ignore($tipoDocumentoLaboral)],
            'descripcion'          => ['nullable', 'string'],
            'icono'                => ['nullable', 'string'],
            'requiere_vencimiento' => ['required', 'boolean'],
            'activo'               => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'               => 'nombre',
            'descripcion'          => 'descripción',
            'icono'                => 'ícono',
            'requiere_vencimiento' => 'requiere vencimiento',
            'activo'               => 'estado',
        ];
    }
}

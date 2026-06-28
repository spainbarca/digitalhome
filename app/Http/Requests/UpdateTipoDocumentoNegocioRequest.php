<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoDocumentoNegocioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $tipoDocumentoNegocio = $this->route('tipoDocumentoNegocio');
        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('tipo_documento_negocio', 'nombre')->ignore($tipoDocumentoNegocio)],
            'icono'  => ['nullable', 'string', 'max:100'],
            'orden'  => ['nullable', 'integer'],
            'activo' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'icono'  => 'ícono',
            'orden'  => 'orden',
            'activo' => 'estado',
        ];
    }
}

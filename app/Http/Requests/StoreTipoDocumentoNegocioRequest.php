<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoDocumentoNegocioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'unique:tipo_documento_negocio,nombre'],
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

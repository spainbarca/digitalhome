<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoDocumentoFinancieroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoDocumentoFinanciero = $this->route('tipoDocumentoFinanciero');

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_documento_financiero', 'nombre')->ignore($tipoDocumentoFinanciero)],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string'],
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTipoDocumentoLegalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'               => ['required', 'string', 'max:255'],
            'descripcion'          => ['nullable', 'string', 'max:500'],
            'icono'                => ['nullable', 'string', 'max:100'],
            'categoria'            => ['required', Rule::in(['personal', 'propiedad', 'seguro', 'contrato', 'denuncia', 'otro'])],
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
            'categoria'            => 'categoría',
            'requiere_vencimiento' => 'requiere vencimiento',
            'activo'               => 'estado',
        ];
    }
}

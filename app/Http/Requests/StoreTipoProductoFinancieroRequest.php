<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoProductoFinancieroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:255', 'unique:tipo_producto_financiero,nombre'],
            'descripcion' => ['nullable', 'string'],
            'naturaleza'  => ['nullable', 'string', 'in:activo,pasivo'],
            'icono'       => ['nullable', 'string'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'naturaleza'  => 'naturaleza',
            'icono'       => 'ícono',
            'activo'      => 'estado',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoProductoFinancieroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoProductoFinanciero = $this->route('tipoProductoFinanciero');

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_producto_financiero', 'nombre')->ignore($tipoProductoFinanciero)],
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

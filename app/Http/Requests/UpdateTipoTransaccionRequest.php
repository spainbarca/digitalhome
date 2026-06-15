<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoTransaccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoTransaccion = $this->route('tipoTransaccion');

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_transaccion', 'nombre')->ignore($tipoTransaccion)],
            'descripcion' => ['nullable', 'string'],
            'naturaleza'  => ['required', 'string', 'in:ingreso,egreso,neutro'],
            'icono'       => ['nullable', 'string'],
            'color'       => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
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
            'color'       => 'color',
            'activo'      => 'estado',
        ];
    }
}

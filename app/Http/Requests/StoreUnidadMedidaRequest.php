<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnidadMedidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'  => ['required', 'string', 'max:80', 'unique:unidad_medida,nombre'],
            'simbolo' => ['required', 'string', 'max:10', 'unique:unidad_medida,simbolo'],
            'icono'   => ['nullable', 'string', 'max:60'],
            'activo'  => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'  => 'nombre',
            'simbolo' => 'símbolo',
            'icono'   => 'ícono',
            'activo'  => 'estado',
        ];
    }
}

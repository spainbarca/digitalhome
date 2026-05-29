<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnidadMedidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $unidad = $this->route('unidad');

        return [
            'nombre'  => ['required', 'string', 'max:80', Rule::unique('unidad_medida', 'nombre')->ignore($unidad)],
            'simbolo' => ['required', 'string', 'max:10', Rule::unique('unidad_medida', 'simbolo')->ignore($unidad)],
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

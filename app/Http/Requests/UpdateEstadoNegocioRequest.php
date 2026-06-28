<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstadoNegocioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $estadoNegocio = $this->route('estadoNegocio');
        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('estado_negocio', 'nombre')->ignore($estadoNegocio)],
            'color'  => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'icono'  => ['nullable', 'string', 'max:100'],
            'orden'  => ['nullable', 'integer'],
            'activo' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'color'  => 'color',
            'icono'  => 'ícono',
            'orden'  => 'orden',
            'activo' => 'estado',
        ];
    }
}

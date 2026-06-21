<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaGastoViajeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $categoriaGastoViaje = $this->route('categoriaGastoViaje');
        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('categoria_gasto_viaje', 'nombre')->ignore($categoriaGastoViaje)],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string'],
            'color'       => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['nombre' => 'nombre', 'descripcion' => 'descripción', 'icono' => 'ícono', 'color' => 'color', 'activo' => 'estado'];
    }
}

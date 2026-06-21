<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstadoReservaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $estadoReserva = $this->route('estadoReserva');
        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('estado_reserva', 'nombre')->ignore($estadoReserva)],
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

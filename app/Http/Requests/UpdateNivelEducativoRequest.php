<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNivelEducativoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nivel = $this->route('nivel');

        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('niveles_educativos', 'nombre')->ignore($nivel)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'icono'       => ['nullable', 'string', 'max:100'],
            'orden'       => ['required', 'integer', 'min:0'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'icono'       => 'ícono',
            'orden'       => 'orden',
            'activo'      => 'estado',
        ];
    }
}

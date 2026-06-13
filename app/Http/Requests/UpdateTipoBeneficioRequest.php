<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoBeneficioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoBeneficio = $this->route('tipoBeneficio');

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('tipo_beneficio', 'nombre')->ignore($tipoBeneficio)],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'icono'       => 'ícono',
            'activo'      => 'estado',
        ];
    }
}

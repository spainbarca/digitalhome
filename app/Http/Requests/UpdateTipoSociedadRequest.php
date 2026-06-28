<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoSociedadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $tipoSociedad = $this->route('tipoSociedad');
        return [
            'sigla'  => ['nullable', 'string', 'max:20'],
            'nombre' => ['required', 'string', 'max:255', Rule::unique('tipo_sociedad', 'nombre')->ignore($tipoSociedad)],
            'icono'  => ['nullable', 'string', 'max:100'],
            'orden'  => ['nullable', 'integer'],
            'activo' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'sigla'  => 'sigla',
            'nombre' => 'nombre',
            'icono'  => 'ícono',
            'orden'  => 'orden',
            'activo' => 'estado',
        ];
    }
}

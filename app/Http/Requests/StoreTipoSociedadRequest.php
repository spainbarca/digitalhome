<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoSociedadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'sigla'  => ['nullable', 'string', 'max:20'],
            'nombre' => ['required', 'string', 'max:255', 'unique:tipo_sociedad,nombre'],
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegimenTributarioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'codigo'      => ['required', 'string', 'max:20', 'unique:regimen_tributario,codigo'],
            'nombre'      => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string', 'max:100'],
            'orden'       => ['nullable', 'integer'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo'      => 'código',
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'icono'       => 'ícono',
            'orden'       => 'orden',
            'activo'      => 'estado',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegimenTributarioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $regimenTributario = $this->route('regimenTributario');
        return [
            'codigo'      => ['required', 'string', 'max:20', Rule::unique('regimen_tributario', 'codigo')->ignore($regimenTributario)],
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

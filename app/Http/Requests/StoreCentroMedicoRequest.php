<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCentroMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id'           => ['required', 'exists:empresas,id'],
            'tipo_centro_medico_id' => ['nullable', 'exists:tipos_centro_medico,id'],
            'nombre_referencial'   => ['nullable', 'string', 'max:150'],
            'sigla'                => ['nullable', 'string', 'max:50'],
            'notas'                => ['nullable', 'string'],
            'imagen'               => ['nullable', 'image', 'max:2048'],
            'activo'               => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'empresa_id'           => 'empresa',
            'tipo_centro_medico_id' => 'tipo de centro médico',
            'nombre_referencial'   => 'nombre referencial',
            'notas'                => 'notas',
            'imagen'               => 'imagen',
        ];
    }
}

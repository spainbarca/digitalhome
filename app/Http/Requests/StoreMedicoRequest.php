<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'especialidad_medica_id' => ['nullable', 'exists:especialidades_medicas,id'],
            'nombres'                => ['required', 'string', 'max:100'],
            'apellidos'              => ['required', 'string', 'max:100'],
            'cmp'                    => ['nullable', 'string', 'max:20'],
            'telefono'               => ['nullable', 'string', 'max:20'],
            'email'                  => ['nullable', 'email', 'max:150'],
            'notas'                  => ['nullable', 'string'],
            'imagen'                 => ['nullable', 'image', 'max:2048'],
            'activo'                 => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'especialidad_medica_id' => 'especialidad médica',
            'nombres'                => 'nombres',
            'apellidos'              => 'apellidos',
            'cmp'                    => 'CMP',
            'telefono'               => 'teléfono',
            'email'                  => 'correo electrónico',
            'notas'                  => 'notas',
            'imagen'                 => 'foto',
        ];
    }
}

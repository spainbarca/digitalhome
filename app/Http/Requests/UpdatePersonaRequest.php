<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombres'            => ['required', 'string', 'max:100'],
            'apellido_paterno'   => ['nullable', 'string', 'max:80'],
            'apellido_materno'   => ['nullable', 'string', 'max:80'],
            'tipo_documento_id'  => ['nullable', 'exists:tbl_tipos_documento,id'],
            'numero_documento'   => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento'   => ['nullable', 'date'],
            'sexo'               => ['nullable', 'in:M,F,otro'],
            'telefono'           => ['nullable', 'string', 'max:20'],
            'email'              => ['nullable', 'email', 'max:150'],
            'tipo_sangre'        => ['nullable', 'string', 'max:5'],
            'activo'             => ['boolean'],
        ];
    }
}

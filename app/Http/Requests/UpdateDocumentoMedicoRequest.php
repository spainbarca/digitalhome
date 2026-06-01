<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentoMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hogar_miembro_id'        => ['required', 'exists:hogar_miembros,id'],
            'consulta_medica_id'      => ['nullable', 'exists:consultas_medicas,id'],
            'tipo_documento_medico_id' => ['nullable', 'exists:tipo_documento_medico,id'],
            'titulo'                  => ['required', 'string', 'max:200'],
            'fecha_documento'         => ['nullable', 'date'],
            'archivo'                 => ['nullable', 'file', 'max:10240'],
            'notas'                   => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'hogar_miembro_id'        => 'miembro del hogar',
            'consulta_medica_id'      => 'consulta médica',
            'tipo_documento_medico_id' => 'tipo de documento',
            'titulo'                  => 'título',
            'fecha_documento'         => 'fecha del documento',
            'archivo'                 => 'archivo',
            'notas'                   => 'notas',
        ];
    }
}

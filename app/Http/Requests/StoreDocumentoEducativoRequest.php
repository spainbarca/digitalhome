<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDocumentoEducativoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'hogar_miembro_id'            => ['required', 'uuid', 'exists:hogar_miembros,id'],
            'matricula_id'                => ['nullable', 'uuid', 'exists:matriculas,id'],
            'tipo_documento_educativo_id' => ['nullable', 'uuid', 'exists:tipo_documento_educativo,id'],
            'titulo'                      => ['required', 'string', 'max:200'],
            'fecha_documento'             => ['nullable', 'date'],
            'archivo'                     => ['nullable', 'file', 'max:10240'],
            'notas'                       => ['nullable', 'string'],
        ];
    }
}

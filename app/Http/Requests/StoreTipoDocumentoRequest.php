<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoDocumentoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'codigo'      => ['required', 'string', 'max:10',  'unique:tipos_documento,codigo'],
            'nombre'      => ['required', 'string', 'max:100', 'unique:tipos_documento,nombre'],
            'descripcion' => ['nullable', 'string', 'max:300'],
            'longitud'    => ['nullable', 'integer', 'min:1', 'max:50'],
            'es_numerico' => ['boolean'],
            'estado'      => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'codigo'   => 'código',
            'longitud' => 'longitud',
        ];
    }
}

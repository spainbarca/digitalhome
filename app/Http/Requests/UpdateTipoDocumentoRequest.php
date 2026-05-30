<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTipoDocumentoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->tipoDocumento->id;
        return [
            'codigo'      => ['required', 'string', 'max:10',  Rule::unique('tipos_documento', 'codigo')->ignore($id)],
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('tipos_documento', 'nombre')->ignore($id)],
            'descripcion' => ['nullable', 'string', 'max:300'],
            'longitud'    => ['nullable', 'integer', 'min:1', 'max:50'],
            'es_numerico' => ['boolean'],
            'estado'      => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['codigo' => 'código', 'longitud' => 'longitud'];
    }
}

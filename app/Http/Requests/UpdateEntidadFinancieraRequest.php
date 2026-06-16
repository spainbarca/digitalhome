<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntidadFinancieraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id'                 => ['required', 'exists:empresas,id'],
            'tipo_entidad_financiera_id' => ['nullable', 'exists:tipo_entidad_financiera,id'],
            'sigla'                      => ['nullable', 'string', 'max:50'],
            'logo_path'                  => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner_path'                => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'codigo_sbs'                 => ['nullable', 'string', 'max:50'],
            'swift'                      => ['nullable', 'string', 'max:20'],
        ];
    }
}

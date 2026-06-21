<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperadorViajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id'              => ['required', 'exists:empresas,id'],
            'tipo_operador_viaje_id'  => ['nullable', 'exists:tipo_operador_viaje,id'],
            'sigla'                   => ['nullable', 'string', 'max:50'],
            'logo_path'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner_path'             => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'codigo_iata'             => ['nullable', 'string', 'max:10'],
        ];
    }
}

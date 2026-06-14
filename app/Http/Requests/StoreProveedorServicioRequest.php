<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProveedorServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id'       => ['nullable', 'exists:empresas,id'],
            'tipo_servicio_id' => ['required', 'exists:tipo_servicio,id'],
            'nombre_comercial' => ['nullable', 'string', 'max:150'],
            'sigla'            => ['nullable', 'string', 'max:50'],
            'telefono'         => ['nullable', 'string', 'max:30'],
            'sitio_web'        => ['nullable', 'url', 'max:200'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'activo'           => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'empresa_id'       => 'empresa',
            'tipo_servicio_id' => 'tipo de servicio',
            'nombre_comercial' => 'nombre comercial',
            'telefono'         => 'teléfono',
            'sitio_web'        => 'sitio web',
            'logo'             => 'logo',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpleadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id'    => ['nullable', 'exists:empresas,id'],
            'nombre'        => ['required', 'string', 'max:255'],
            'descripcion'   => ['nullable', 'string'],
            'telefono'      => ['nullable', 'string', 'max:30'],
            'email'         => ['nullable', 'email', 'max:150'],
            'sitio_web'     => ['nullable', 'url', 'max:255'],
            'direccion'     => ['nullable', 'string', 'max:500'],
            'distrito_inei' => ['nullable', 'string', 'exists:ubigeo_distritos,inei'],
            'logo_path'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen_path'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner_path'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'activo'        => ['nullable', 'boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntidadLegalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'                => 'required|string|max:200',
            'sigla'                 => 'nullable|string|max:50',
            'tipo_entidad_legal_id' => 'required|uuid|exists:tipo_entidad_legal,id',
            'empresa_id'            => 'nullable|uuid|exists:empresas,id',
            'descripcion'           => 'nullable|string|max:1000',
            'telefono'              => 'nullable|string|max:30',
            'email'                 => 'nullable|email|max:150',
            'sitio_web'             => 'nullable|url|max:200',
            'direccion'             => 'nullable|string|max:300',
            'distrito_inei'         => 'nullable|string|size:6|exists:ubigeo_distritos,inei',
            'logo'                  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'imagen'                => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'banner'                => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'activo'                => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'                => 'nombre',
            'tipo_entidad_legal_id' => 'tipo de entidad',
            'empresa_id'            => 'empresa',
            'email'                 => 'email',
            'sitio_web'             => 'sitio web',
            'distrito_inei'         => 'distrito',
        ];
    }
}

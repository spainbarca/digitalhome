<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sector_id'                => ['nullable', 'exists:sectores,id'],
            'tipo_contribuyente_id'    => ['nullable', 'exists:tipo_contribuyente,id'],
            'ruc'                      => ['required', 'digits:11', 'unique:empresas,ruc'],
            'razon_social'             => ['required', 'string', 'max:200'],
            'nombre_comercial'         => ['nullable', 'string', 'max:200'],
            'sigla'                    => ['nullable', 'string', 'max:50'],
            'estado_sunat'             => ['nullable', 'string', 'max:50'],
            'condicion_sunat'          => ['nullable', 'string', 'max:50'],
            'direccion_fiscal'         => ['nullable', 'string'],
            'distrito_inei'            => ['nullable', 'exists:ubigeo_distritos,inei'],
            'fecha_inicio_actividades' => ['nullable', 'date'],
            'telefono'                 => ['nullable', 'string', 'max:20'],
            'sitio_web'                => ['nullable', 'url', 'max:200'],
            'email'                    => ['nullable', 'email', 'max:150'],
            'logo'                     => ['nullable', 'image', 'max:2048'],
            'activo'                   => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'sector_id'                => 'sector',
            'tipo_contribuyente_id'    => 'tipo de contribuyente',
            'ruc'                      => 'RUC',
            'razon_social'             => 'razón social',
            'nombre_comercial'         => 'nombre comercial',
            'estado_sunat'             => 'estado SUNAT',
            'condicion_sunat'          => 'condición SUNAT',
            'direccion_fiscal'         => 'dirección fiscal',
            'distrito_inei'            => 'distrito',
            'fecha_inicio_actividades' => 'fecha de inicio de actividades',
            'telefono'                 => 'teléfono',
            'sitio_web'                => 'sitio web',
            'email'                    => 'correo electrónico',
            'logo'                     => 'logo',
        ];
    }
}

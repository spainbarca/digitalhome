<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreComercioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'empresa_id'       => ['required', 'exists:empresas,id'],
            'tipo_comercio_id' => ['nullable', 'exists:tipo_comercio,id'],
            'nombre_referencial' => ['nullable', 'string', 'max:255'],
            'es_online'        => ['boolean'],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'pais_iso2'        => ['nullable', 'string', 'size:2', 'exists:paises,iso2'],
            'ciudad_id'        => ['nullable', 'integer', 'exists:ciudades,id'],
            'distrito_inei'    => ['nullable', 'string', 'exists:ubigeo_distritos,inei'],
            'notas'            => ['nullable', 'string'],
            'activo'           => ['boolean'],
            'logo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'imagen'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'empresa_id'         => 'empresa',
            'tipo_comercio_id'   => 'tipo de comercio',
            'nombre_referencial' => 'nombre referencial',
            'es_online'          => 'es online',
            'direccion'          => 'dirección',
            'pais_iso2'          => 'país',
            'ciudad_id'          => 'ciudad',
            'distrito_inei'      => 'distrito',
            'notas'              => 'notas',
            'activo'             => 'estado',
            'logo'               => 'logo',
            'imagen'             => 'imagen',
            'banner'             => 'banner',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropiedadInmuebleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_inmueble_id' => ['required', 'exists:tipo_inmueble,id'],
            'alias'            => ['required', 'string', 'max:100'],
            'direccion'        => ['required', 'string'],
            'referencia'       => ['nullable', 'string', 'max:200'],
            'activo'           => ['boolean'],
            'ubicacion_tipo'   => ['required', 'in:peru,extranjero'],
            'distrito_inei'    => ['nullable', 'required_if:ubicacion_tipo,peru', 'exists:ubigeo_distritos,inei'],
            'pais_iso2'        => ['nullable', 'required_if:ubicacion_tipo,extranjero', 'exists:paises,iso2'],
            'ciudad_id'        => ['nullable', 'exists:ciudades,id'],
            'avatar'           => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_inmueble_id' => 'tipo de inmueble',
            'alias'            => 'alias',
            'direccion'        => 'dirección',
            'referencia'       => 'referencia',
            'activo'           => 'estado',
            'ubicacion_tipo'   => 'tipo de ubicación',
            'distrito_inei'    => 'distrito',
            'pais_iso2'        => 'país',
            'ciudad_id'        => 'ciudad',
            'avatar'           => 'imagen',
        ];
    }
}

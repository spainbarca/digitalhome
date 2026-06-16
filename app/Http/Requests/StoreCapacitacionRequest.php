<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCapacitacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hogar_miembro_id'         => ['required', 'exists:hogar_miembros,id'],
            'empleo_id'                => ['nullable', 'exists:empleos,id'],
            'tipo_capacitacion_id'     => ['nullable', 'exists:tipo_capacitacion,id'],
            'institucion_educativa_id' => ['nullable', 'exists:instituciones_educativas,id'],
            'nombre'                   => ['required', 'string', 'max:255'],
            'descripcion'              => ['nullable', 'string'],
            'institucion_nombre'       => ['nullable', 'string', 'max:255'],
            'fecha_inicio'             => ['nullable', 'date'],
            'fecha_fin'                => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'fecha_vencimiento'        => ['nullable', 'date'],
            'codigo_certificado'       => ['nullable', 'string', 'max:255'],
            'url_verificacion'         => ['nullable', 'url', 'max:255'],
            'horas_academicas'         => ['nullable', 'integer', 'min:0'],
            'notas'                    => ['nullable', 'string'],
            'archivo'                  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'activo'                   => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'hogar_miembro_id'         => 'miembro del hogar',
            'empleo_id'                => 'empleo',
            'tipo_capacitacion_id'     => 'tipo de capacitación',
            'institucion_educativa_id' => 'institución educativa',
            'nombre'                   => 'nombre',
            'fecha_fin'                => 'fecha de fin',
            'fecha_vencimiento'        => 'fecha de vencimiento',
            'url_verificacion'         => 'URL de verificación',
            'horas_academicas'         => 'horas académicas',
            'archivo'                  => 'certificado',
        ];
    }
}

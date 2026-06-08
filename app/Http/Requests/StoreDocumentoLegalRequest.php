<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoLegalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_documento_legal_id'   => 'required|uuid|exists:tipo_documento_legal,id',
            'estado_documento_legal_id' => 'required|uuid|exists:estado_documento_legal,id',
            'hogar_miembro_id'          => 'nullable|uuid|exists:hogar_miembros,id',
            'propiedad_inmueble_id'     => 'nullable|uuid|exists:propiedades_inmueble,id',
            'entidad_legal_id'          => 'nullable|uuid|exists:entidades_legales,id',
            'titulo'                    => 'required|string|max:255',
            'numero_documento'          => 'nullable|string|max:100',
            'fecha_emision'             => 'nullable|date',
            'fecha_vencimiento'         => 'nullable|date|after:fecha_emision',
            'notas'                     => 'nullable|string|max:2000',
            'activo'                    => 'nullable|boolean',
            'archivo'                   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_documento_legal_id'   => 'tipo de documento',
            'estado_documento_legal_id' => 'estado',
            'hogar_miembro_id'          => 'miembro',
            'propiedad_inmueble_id'     => 'propiedad',
            'entidad_legal_id'          => 'entidad legal',
            'titulo'                    => 'título',
            'numero_documento'          => 'número de documento',
            'fecha_emision'             => 'fecha de emisión',
            'fecha_vencimiento'         => 'fecha de vencimiento',
        ];
    }
}

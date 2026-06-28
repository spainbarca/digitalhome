<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNegocioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'empresa_id'               => ['nullable', 'exists:empresas,id'],
            'miembro_id'               => ['nullable', 'exists:personas,id'],
            'regimen_tributario_id'    => ['nullable', 'exists:regimen_tributario,id'],
            'tipo_sociedad_id'         => ['nullable', 'exists:tipo_sociedad,id'],
            'estado_negocio_id'        => ['nullable', 'exists:estado_negocio,id'],
            'nombre'                   => ['nullable', 'string', 'max:255'],
            'ciiu'                     => ['nullable', 'string', 'max:20'],
            'fecha_constitucion'       => ['nullable', 'date'],
            'fecha_inicio_actividades' => ['nullable', 'date'],
            'partida_registral'        => ['nullable', 'string', 'max:100'],
            'oficina_registral'        => ['nullable', 'string', 'max:100'],
            'observaciones'            => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'empresa_id'               => 'empresa',
            'miembro_id'               => 'representante legal',
            'regimen_tributario_id'    => 'régimen tributario',
            'tipo_sociedad_id'         => 'tipo de sociedad',
            'estado_negocio_id'        => 'estado del negocio',
            'nombre'                   => 'nombre',
            'ciiu'                     => 'código CIIU',
            'fecha_constitucion'       => 'fecha de constitución',
            'fecha_inicio_actividades' => 'fecha de inicio de actividades',
            'partida_registral'        => 'partida registral',
            'oficina_registral'        => 'oficina registral',
            'observaciones'            => 'observaciones',
        ];
    }
}

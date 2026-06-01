<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultaMedicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hogar_miembro_id'      => ['required', 'exists:hogar_miembros,id'],
            'medico_id'             => ['nullable', 'exists:medicos,id'],
            'centro_medico_id'      => ['nullable', 'exists:centros_medicos,id'],
            'especialidad_medica_id' => ['nullable', 'exists:especialidades_medicas,id'],
            'moneda_id'             => ['nullable', 'exists:monedas,id'],
            'fecha'                 => ['required', 'date'],
            'hora'                  => ['nullable', 'date_format:H:i'],
            'motivo'                => ['nullable', 'string', 'max:255'],
            'diagnostico'           => ['nullable', 'string'],
            'tratamiento_indicado'  => ['nullable', 'string'],
            'costo'                 => ['nullable', 'numeric', 'min:0'],
            'notas'                 => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'hogar_miembro_id'       => 'miembro del hogar',
            'medico_id'              => 'médico',
            'centro_medico_id'       => 'centro médico',
            'especialidad_medica_id' => 'especialidad',
            'moneda_id'              => 'moneda',
            'fecha'                  => 'fecha',
            'hora'                   => 'hora',
            'motivo'                 => 'motivo',
            'diagnostico'            => 'diagnóstico',
            'tratamiento_indicado'   => 'tratamiento indicado',
            'costo'                  => 'costo',
            'notas'                  => 'notas',
        ];
    }
}

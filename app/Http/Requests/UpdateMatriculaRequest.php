<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'hogar_miembro_id'        => 'required|exists:hogar_miembros,id',
            'institucion_educativa_id' => 'nullable|exists:instituciones_educativas,id',
            'nivel_educativo_id'       => 'nullable|exists:niveles_educativos,id',
            'turno_educativo_id'       => 'nullable|exists:turnos_educativos,id',
            'estado_matricula_id'      => 'nullable|exists:estados_matricula,id',
            'moneda_id'                => 'nullable|exists:monedas,id',
            'año_lectivo'              => 'nullable|string|max:20',
            'grado_ciclo'              => 'nullable|string|max:100',
            'nombre_tutor'             => 'nullable|string|max:150',
            'telefono_tutor'           => 'nullable|string|max:20',
            'costo_mensual'            => 'nullable|numeric|min:0',
            'dia_vencimiento_pago'     => 'nullable|integer|min:1|max:31',
            'fecha_inicio'             => 'nullable|date',
            'fecha_fin'                => 'nullable|date|after_or_equal:fecha_inicio',
            'notas'                    => 'nullable|string|max:5000',
        ];
    }
}

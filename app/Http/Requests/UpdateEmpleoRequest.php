<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpleoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hogar_miembro_id'           => ['required', 'exists:hogar_miembros,id'],
            'estado_empleo_id'           => ['required', 'exists:estado_empleo,id'],
            'empleador_id'               => ['nullable', 'exists:empleadores,id'],
            'modalidad_laboral_id'       => ['nullable', 'exists:modalidad_laboral,id'],
            'cargo'                      => ['required', 'string', 'max:255'],
            'descripcion'                => ['nullable', 'string'],
            'fecha_inicio'               => ['nullable', 'date'],
            'fecha_fin'                  => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'salario'                    => ['nullable', 'numeric', 'min:0'],
            'moneda_id'                  => ['nullable', 'exists:monedas,id'],
            'es_actual'                  => ['boolean'],
            'activo'                     => ['boolean'],
            'logros'                     => ['nullable', 'string'],
            'fecha_vencimiento_contrato' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'es_actual' => $this->boolean('es_actual'),
            'activo'    => $this->boolean('activo'),
        ]);
    }
}

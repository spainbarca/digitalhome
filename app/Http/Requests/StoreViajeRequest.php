<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'        => ['required', 'string', 'max:255'],
            'tipo_viaje_id' => ['nullable', 'exists:tipo_viaje,id'],
            'descripcion'   => ['nullable', 'string'],
            'fecha_inicio'  => ['nullable', 'date'],
            'fecha_fin'     => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado'        => ['nullable', 'string', 'in:planificado,en_curso,completado,cancelado'],
            'presupuesto'   => ['nullable', 'numeric', 'min:0'],
            'moneda_id'     => ['nullable', 'exists:monedas,id'],
            'portada'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'notas'         => ['nullable', 'string'],
        ];
    }
}

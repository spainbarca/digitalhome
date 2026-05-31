<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecordatorioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'titulo'            => ['required', 'string', 'max:150'],
            'descripcion'       => ['nullable', 'string', 'max:500'],
            'fecha_vencimiento' => ['required', 'date'],
            'fecha_aviso'       => ['nullable', 'date', 'before_or_equal:fecha_vencimiento'],
            'estado'            => ['required', 'in:pendiente,enviado,descartado'],
            'repetir'           => ['boolean'],
            'recordable_id'     => ['nullable', 'string'],
            'recordable_type'   => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'titulo'            => 'título',
            'fecha_vencimiento' => 'fecha de vencimiento',
            'fecha_aviso'       => 'fecha de aviso',
            'estado'            => 'estado',
        ];
    }
}

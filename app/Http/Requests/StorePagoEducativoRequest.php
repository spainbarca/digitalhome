<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePagoEducativoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'matricula_id'        => ['required', 'uuid', 'exists:matriculas,id'],
            'moneda_id'           => ['nullable', 'integer', 'exists:monedas,id'],
            'concepto'            => ['nullable', 'string', 'max:200'],
            'mes_correspondiente' => ['nullable', 'string', 'max:20'],
            'monto'               => ['required', 'numeric', 'min:0'],
            'fecha_vencimiento'   => ['nullable', 'date'],
            'fecha_pago'          => ['nullable', 'date'],
            'estado'              => ['required', 'in:pendiente,pagado,vencido'],
            'comprobante'         => ['nullable', 'file', 'max:10240'],
            'notas'               => ['nullable', 'string'],
        ];
    }
}

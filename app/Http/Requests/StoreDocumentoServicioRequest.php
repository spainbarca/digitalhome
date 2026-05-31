<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cuenta_id'         => ['required', 'uuid', 'exists:cuentas_servicio,id'],
            'tipo_documento_id' => ['required', 'uuid', 'exists:tipo_documento_servicio,id'],
            'estado_pago_id'    => ['required', 'uuid', 'exists:estado_pago,id'],
            'metodo_pago_id'    => ['nullable', 'uuid', 'exists:metodo_pago,id'],
            'moneda_id'         => ['required', 'exists:monedas,id'],
            'visibilidad_id'    => ['required', 'uuid', 'exists:tipo_visibilidad,id'],
            'periodo_inicio'    => ['required', 'date'],
            'periodo_fin'       => ['required', 'date', 'after_or_equal:periodo_inicio'],
            'monto_total'       => ['required', 'numeric', 'min:0'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'fecha_pago'        => ['nullable', 'date'],
            'notas'             => ['nullable', 'string', 'max:1000'],
            'archivo'                  => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'documento'                => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'documento_extension'      => ['nullable'],
            'documento_tamano_bytes'   => ['nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'cuenta_id'         => 'cuenta de servicio',
            'tipo_documento_id' => 'tipo de documento',
            'estado_pago_id'    => 'estado de pago',
            'moneda_id'         => 'moneda',
            'visibilidad_id'    => 'visibilidad',
            'periodo_inicio'    => 'período inicio',
            'periodo_fin'       => 'período fin',
            'monto_total'       => 'monto total',
            'fecha_vencimiento' => 'fecha de vencimiento',
            'fecha_pago'        => 'fecha de pago',
            'archivo'           => 'archivo',
        ];
    }
}

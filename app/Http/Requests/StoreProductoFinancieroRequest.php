<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoFinancieroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entidad_financiera_id'       => ['required', 'exists:entidades_financieras,id'],
            'tipo_producto_financiero_id' => ['required', 'exists:tipo_producto_financiero,id'],
            'estado_producto_id'          => ['required', 'exists:estado_producto,id'],
            'moneda_id'                   => ['required', 'exists:monedas,id'],
            'miembro_id'                  => ['required', 'exists:hogar_miembros,id'],
            'producto_padre_id'           => ['nullable', 'exists:productos_financieros,id'],
            'alias'                       => ['required', 'string', 'max:255'],
            'es_mancomunada'               => ['nullable', 'boolean'],

            'numero_cuenta'      => ['nullable', 'string', 'max:255'],
            'cci'                => ['nullable', 'string', 'max:255'],
            'ultimos_digitos'    => ['nullable', 'string', 'max:4'],

            'saldo_actual'    => ['nullable', 'numeric'],
            'linea_credito'   => ['nullable', 'numeric'],
            'tasa_tea'        => ['nullable', 'numeric'],
            'tasa_tcea'       => ['nullable', 'numeric'],
            'cuota'           => ['nullable', 'numeric'],
            'plazo_meses'     => ['nullable', 'integer', 'min:0'],
            'dia_corte'       => ['nullable', 'integer', 'min:1', 'max:31'],
            'dia_pago'        => ['nullable', 'integer', 'min:1', 'max:31'],
            'fecha_apertura'    => ['nullable', 'date'],
            'fecha_vencimiento' => ['nullable', 'date'],

            'notas' => ['nullable', 'string'],
        ];
    }
}

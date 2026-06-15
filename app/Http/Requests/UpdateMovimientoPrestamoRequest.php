<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMovimientoPrestamoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'tipo'             => ['required', 'in:cargo,abono,descuento'],
            'concepto_pago_id' => ['nullable', 'exists:conceptos_pago,id'],
            'monto'            => ['required', 'numeric', 'min:0.01', 'max:9999999.99'],
            'glosa'            => ['nullable', 'string', 'max:255'],
            'fecha'            => ['required', 'date'],
            'metodo_pago_id'   => ['nullable', 'exists:metodo_pago,id'],
            'comprobante'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'notas'            => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo'             => 'tipo de movimiento',
            'concepto_pago_id' => 'concepto de pago',
            'monto'            => 'monto',
            'glosa'            => 'glosa',
            'fecha'            => 'fecha',
            'metodo_pago_id'   => 'método de pago',
            'comprobante'      => 'comprobante',
            'notas'            => 'notas',
        ];
    }
}

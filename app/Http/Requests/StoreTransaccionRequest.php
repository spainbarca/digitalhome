<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTransaccionRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'tipo_transaccion_id' => ['required', 'exists:tipo_transaccion,id'],
            'monto'               => ['required', 'numeric', 'min:0.01'],
            'moneda_id'           => ['required', 'exists:monedas,id'],
            'fecha'               => ['required', 'date'],
            'glosa'               => ['nullable', 'string', 'max:255'],
            'nro_operacion'       => ['nullable', 'string', 'max:100'],
            'metodo_pago_id'      => ['nullable', 'exists:metodo_pago,id'],
            'producto_destino_id' => ['nullable', 'exists:productos_financieros,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_transaccion_id' => 'tipo de transacción',
            'monto'               => 'monto',
            'moneda_id'           => 'moneda',
            'fecha'               => 'fecha',
            'glosa'               => 'glosa',
            'nro_operacion'       => 'número de operación',
            'metodo_pago_id'      => 'método de pago',
            'producto_destino_id' => 'cuenta destino',
        ];
    }
}

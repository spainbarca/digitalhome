<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCompraRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'miembro_id'          => ['required', 'exists:hogar_miembros,id'],
            'comercio_id'         => ['nullable', 'exists:comercios,id'],
            'categoria_compra_id' => ['required', 'exists:categorias_compra,id'],
            'metodo_pago_id'      => ['required', 'exists:metodo_pago,id'],
            'moneda_id'           => ['required', 'exists:monedas,id'],
            'fecha_compra'        => ['required', 'date'],
            'total'               => ['required', 'numeric', 'min:0'],
            'concepto'            => ['required', 'string', 'max:255'],
            'notas'               => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'miembro_id'          => 'miembro',
            'comercio_id'         => 'comercio',
            'categoria_compra_id' => 'categoría',
            'metodo_pago_id'      => 'método de pago',
            'moneda_id'           => 'moneda',
            'fecha_compra'        => 'fecha de compra',
            'total'               => 'total',
            'concepto'            => 'concepto',
            'notas'               => 'notas',
        ];
    }
}

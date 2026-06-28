<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'negocio_id'           => ['required', 'exists:negocios,id'],
            'proveedor_negocio_id' => ['required', 'exists:proveedores_negocio,id'],
            'numero'               => ['nullable', 'string', 'max:100'],
            'descripcion'          => ['required', 'string'],
            'fecha'                => ['required', 'date'],
            'moneda_id'            => ['nullable', 'exists:monedas,id'],
            'total'                => ['nullable', 'numeric', 'min:0'],
            'observaciones'        => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'negocio_id.required'          => 'Selecciona un negocio.',
            'negocio_id.exists'            => 'Negocio no válido.',
            'proveedor_negocio_id.required' => 'Selecciona un proveedor.',
            'descripcion.required'          => 'La descripción es obligatoria.',
            'fecha.required'                => 'La fecha es obligatoria.',
            'total.numeric'                 => 'El total debe ser un número.',
            'total.min'                     => 'El total no puede ser negativo.',
        ];
    }
}

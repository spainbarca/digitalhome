<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCuentaServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedor_id'    => ['required', 'exists:proveedores_servicio,id'],
            'numero_cuenta'   => ['required', 'string', 'max:100'],
            'titular_user_id' => ['nullable', 'exists:users,id'],
            'estado'          => ['required', 'in:activa,suspendida,cancelada'],
            'fecha_inicio'    => ['nullable', 'date'],
            'notas'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'proveedor_id'    => 'proveedor',
            'numero_cuenta'   => 'número de cuenta / suministro',
            'titular_user_id' => 'titular',
            'estado'          => 'estado',
            'fecha_inicio'    => 'fecha de inicio',
            'notas'           => 'notas',
        ];
    }
}

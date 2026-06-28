<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProveedorNegocioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'empresa_id'      => ['nullable', 'exists:empresas,id'],
            'nombre'          => ['required', 'string', 'max:255'],
            'sigla'           => ['nullable', 'string', 'max:50'],
            'condicion_pago'  => ['nullable', 'string', 'in:contado,crédito'],
            'contacto'        => ['nullable', 'string', 'max:255'],
            'telefono'        => ['nullable', 'string', 'max:50'],
            'logo_path'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner_path'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'activo'          => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'empresa_id'     => 'empresa',
            'nombre'         => 'nombre',
            'sigla'          => 'sigla',
            'condicion_pago' => 'condición de pago',
            'contacto'       => 'contacto',
            'telefono'       => 'teléfono',
            'logo_path'      => 'logo',
            'banner_path'    => 'banner',
            'activo'         => 'estado',
        ];
    }
}

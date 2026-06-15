<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreConceptoPagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'categoria_concepto_id' => ['nullable', 'exists:categorias_concepto,id'],
            'nombre'                => ['required', 'string', 'max:255'],
            'descripcion'           => ['nullable', 'string'],
            'precio_referencial'    => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'imagen'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'activo'                => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'categoria_concepto_id' => 'categoría',
            'nombre'                => 'nombre',
            'descripcion'           => 'descripción',
            'precio_referencial'    => 'precio referencial',
            'imagen'                => 'imagen',
            'activo'                => 'estado',
        ];
    }
}

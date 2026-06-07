<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:255'],
            'icono'       => ['nullable', 'string', 'max:255'],
            'activo'      => ['required', 'boolean'],
            'descripcion' => ['nullable', 'string'],
            'imagen'      => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'icono'       => 'ícono',
            'activo'      => 'estado',
            'descripcion' => 'descripción',
            'imagen'      => 'imagen',
        ];
    }
}

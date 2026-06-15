<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaConceptoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoriaConcepto = $this->route('categoriaConcepto');

        return [
            'nombre'      => ['required', 'string', 'max:255', Rule::unique('categorias_concepto', 'nombre')->ignore($categoriaConcepto)],
            'descripcion' => ['nullable', 'string'],
            'icono'       => ['nullable', 'string'],
            'activo'      => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'descripcion' => 'descripción',
            'icono'       => 'ícono',
            'activo'      => 'estado',
        ];
    }
}

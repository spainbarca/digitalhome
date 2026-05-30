<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:120', Rule::unique('sectores', 'nombre')->ignore($this->sector)],
            'icono'       => ['nullable', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:300'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'      => 'nombre',
            'icono'       => 'ícono',
            'descripcion' => 'descripción',
        ];
    }
}

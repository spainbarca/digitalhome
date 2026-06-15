<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePrestatarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:255'],
            'es_miembro_hogar' => ['boolean'],
            'hogar_miembro_id' => ['nullable', 'exists:hogar_miembros,id'],
            'moneda_id'        => ['required', 'exists:monedas,id'],
            'telefono'         => ['nullable', 'string', 'max:30'],
            'foto'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'notas'            => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'           => 'nombre',
            'es_miembro_hogar' => 'es miembro del hogar',
            'hogar_miembro_id' => 'miembro del hogar',
            'moneda_id'        => 'moneda',
            'telefono'         => 'teléfono',
            'foto'             => 'foto',
            'notas'            => 'notas',
        ];
    }
}

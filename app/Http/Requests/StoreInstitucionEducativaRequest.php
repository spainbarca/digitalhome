<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInstitucionEducativaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'empresa_id'                    => 'nullable|exists:empresas,id',
            'tipo_institucion_educativa_id' => 'nullable|exists:tipo_institucion_educativa,id',
            'nombre_referencial'            => 'nullable|string|max:255',
            'sigla'                         => 'nullable|string|max:50',
            'logo'                          => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'imagen'                        => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'notas'                         => 'nullable|string|max:5000',
            'activo'                        => 'boolean',
        ];
    }
}

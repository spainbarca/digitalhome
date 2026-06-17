<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBeneficiarioRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'hogar_miembro_id'    => ['nullable', 'exists:hogar_miembros,id'],
            'nombre'              => ['required', 'string', 'max:255'],
            'parentesco'          => ['nullable', 'string', 'max:50'],
            'documento_identidad' => ['nullable', 'string', 'max:20'],
            'porcentaje'          => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'hogar_miembro_id'    => 'miembro del hogar',
            'nombre'              => 'nombre',
            'parentesco'          => 'parentesco',
            'documento_identidad' => 'documento de identidad',
            'porcentaje'          => 'porcentaje',
        ];
    }
}

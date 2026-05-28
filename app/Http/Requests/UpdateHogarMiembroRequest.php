<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHogarMiembroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'persona_id'    => ['required', 'exists:personas,id'],
            'parentesco_id' => ['required', 'exists:parentescos,id'],
            'es_titular'    => ['boolean'],
            'apodo'         => ['nullable', 'string', 'max:60'],
        ];
    }
}

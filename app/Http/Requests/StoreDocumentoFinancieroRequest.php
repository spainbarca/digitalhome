<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDocumentoFinancieroRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'tipo_documento_financiero_id' => ['required', 'exists:tipo_documento_financiero,id'],
            'titulo'                       => ['required', 'string', 'max:255'],
            'periodo'                      => ['nullable', 'string', 'max:20'],
            'fecha_emision'                => ['nullable', 'date'],
            'fecha_vencimiento'            => ['nullable', 'date'],
            'archivo'                      => ['nullable', 'file', 'max:20480'],
            'notas'                        => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_documento_financiero_id' => 'tipo de documento',
            'titulo'                       => 'título',
            'periodo'                      => 'período',
            'fecha_emision'                => 'fecha de emisión',
            'fecha_vencimiento'            => 'fecha de vencimiento',
            'archivo'                      => 'archivo',
            'notas'                        => 'notas',
        ];
    }
}

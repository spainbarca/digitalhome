<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDocumentoCompraRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'tipo_documento_compra_id' => ['required', 'exists:tipo_documento_compra,id'],
            'titulo'                   => ['required', 'string', 'max:255'],
            'archivo'                  => ['nullable', 'file', 'max:8192'],
            'fecha_documento'          => ['nullable', 'date'],
            'fecha_vencimiento'        => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_documento_compra_id' => 'tipo de documento',
            'titulo'                   => 'título',
            'archivo'                  => 'archivo',
            'fecha_documento'          => 'fecha del documento',
            'fecha_vencimiento'        => 'fecha de vencimiento',
        ];
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DocumentoLaboral;
use App\Models\Empleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoLaboralController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function rules(): array
    {
        return [
            'tipo_documento_laboral_id' => ['required', 'exists:tipo_documento_laboral,id'],
            'titulo'                    => ['required', 'string', 'max:255'],
            'numero_documento'          => ['nullable', 'string', 'max:100'],
            'periodo_mes'               => ['nullable', 'integer', 'min:1', 'max:12'],
            'periodo_anio'              => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'fecha_emision'             => ['nullable', 'date'],
            'fecha_vencimiento'         => ['nullable', 'date', 'after_or_equal:fecha_emision'],
            'notas'                     => ['nullable', 'string'],
            'archivo'                   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'activo'                    => ['nullable', 'boolean'],
        ];
    }

    public function store(Request $request, Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $data = $request->validate($this->rules());

        if ($request->hasFile('archivo')) {
            $data['file_path'] = $request->file('archivo')->store('documentos-laborales', 'public');
        }
        unset($data['archivo']);

        $empleo->documentosLaborales()->create($data);

        return back()->with('success', 'Documento agregado correctamente.');
    }

    public function update(Request $request, DocumentoLaboral $documento)
    {
        abort_unless($documento->empleo?->hogar_id === $this->hogarId(), 403);

        $rules = $this->rules();
        $data  = $request->validate($rules);

        if ($request->hasFile('archivo')) {
            if ($documento->file_path) Storage::disk('public')->delete($documento->file_path);
            $data['file_path'] = $request->file('archivo')->store('documentos-laborales', 'public');
        }
        unset($data['archivo']);

        $documento->update($data);

        return back()->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(DocumentoLaboral $documento)
    {
        abort_unless($documento->empleo?->hogar_id === $this->hogarId(), 403);

        if ($documento->file_path) Storage::disk('public')->delete($documento->file_path);
        $documento->delete();

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}

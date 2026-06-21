<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DocumentoViaje;
use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoViajeController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Viaje $viaje): RedirectResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'tipo_documento_viaje_id' => ['required', 'exists:tipo_documento_viaje,id'],
            'reserva_id'              => ['nullable', 'exists:reservas,id'],
            'nombre'                  => ['required', 'string', 'max:255'],
            'fecha_emision'           => ['nullable', 'date'],
            'fecha_vencimiento'       => ['nullable', 'date'],
            'archivo'                 => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,gif,webp', 'max:20480'],
            'notas'                   => ['nullable', 'string'],
        ]);

        if ($request->hasFile('archivo')) {
            $data['archivo_path'] = $request->file('archivo')->store('viajes/documentos', 'public');
        }
        unset($data['archivo']);

        $data['viaje_id'] = $viaje->id;
        $data['hogar_id'] = $this->hogarId();
        DocumentoViaje::create($data);

        return redirect()->route('dashboard.viajes.show', $viaje)
            ->with('success', 'Documento agregado correctamente.')
            ->with('activeTab', 'tabDocumentos');
    }

    public function update(Request $request, DocumentoViaje $documentoViaje): RedirectResponse
    {
        abort_unless($documentoViaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'tipo_documento_viaje_id' => ['required', 'exists:tipo_documento_viaje,id'],
            'reserva_id'              => ['nullable', 'exists:reservas,id'],
            'nombre'                  => ['required', 'string', 'max:255'],
            'fecha_emision'           => ['nullable', 'date'],
            'fecha_vencimiento'       => ['nullable', 'date'],
            'archivo'                 => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,gif,webp', 'max:20480'],
            'notas'                   => ['nullable', 'string'],
        ]);

        if ($request->hasFile('archivo')) {
            if ($documentoViaje->archivo_path) {
                Storage::disk('public')->delete($documentoViaje->archivo_path);
            }
            $data['archivo_path'] = $request->file('archivo')->store('viajes/documentos', 'public');
        }
        unset($data['archivo']);

        $documentoViaje->update($data);

        return redirect()->route('dashboard.viajes.show', $documentoViaje->viaje_id)
            ->with('success', 'Documento actualizado correctamente.')
            ->with('activeTab', 'tabDocumentos');
    }

    public function destroy(DocumentoViaje $documentoViaje): RedirectResponse
    {
        abort_unless($documentoViaje->hogar_id === $this->hogarId(), 403);

        $viajeId = $documentoViaje->viaje_id;
        if ($documentoViaje->archivo_path) {
            Storage::disk('public')->delete($documentoViaje->archivo_path);
        }
        $documentoViaje->delete();

        return redirect()->route('dashboard.viajes.show', $viajeId)
            ->with('success', 'Documento eliminado correctamente.')
            ->with('activeTab', 'tabDocumentos');
    }
}

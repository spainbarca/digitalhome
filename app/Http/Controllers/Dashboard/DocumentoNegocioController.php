<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DocumentoNegocio;
use App\Models\Negocio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoNegocioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        abort_unless($negocio->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'negocio_id'               => ['nullable', 'exists:negocios,id'],
            'pedido_id'                => ['nullable', 'exists:pedidos,id'],
            'pago_negocio_id'          => ['nullable', 'exists:pagos_negocio,id'],
            'tipo_documento_negocio_id'=> ['nullable', 'exists:tipo_documento_negocio,id'],
            'nombre'                   => ['required', 'string', 'max:255'],
            'archivo'                  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx', 'max:10240'],
            'fecha_emision'            => ['nullable', 'date'],
            'fecha_vencimiento'        => ['nullable', 'date'],
            'observaciones'            => ['nullable', 'string'],
        ]);

        $contextos = array_filter([
            $data['negocio_id']      ?? null,
            $data['pedido_id']       ?? null,
            $data['pago_negocio_id'] ?? null,
        ]);

        if (count($contextos) !== 1) {
            return back()
                ->withInput()
                ->withErrors(['contexto' => 'Debe especificarse exactamente un contexto (negocio, pedido o pago).'])
                ->with('activeTab', 'tabDocumentos');
        }

        // Determine which tab to return to
        $activeTab = 'tabDocumentos';
        if (!empty($data['pedido_id']))       $activeTab = 'tabPedidos';
        elseif (!empty($data['pago_negocio_id'])) $activeTab = 'tabPagos';

        unset($data['archivo']);
        $doc = DocumentoNegocio::create($data);

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $path = $file->storeAs("documentos_negocio/{$doc->id}", 'archivo.' . $file->getClientOriginalExtension(), 'public');
            $doc->update(['archivo_path' => $path]);
        }

        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Documento adjuntado correctamente.')
            ->with('activeTab', $activeTab);
    }

    public function destroy(DocumentoNegocio $documentoNegocio): RedirectResponse
    {
        $negocio = $documentoNegocio->negocio
            ?? $documentoNegocio->pedido?->negocio
            ?? $documentoNegocio->pagoNegocio?->negocio;

        abort_unless($negocio?->hogar_id === $this->hogarId(), 403);

        if ($documentoNegocio->archivo_path) {
            Storage::disk('public')->delete($documentoNegocio->archivo_path);
        }

        $activeTab = 'tabDocumentos';
        if ($documentoNegocio->pedido_id)       $activeTab = 'tabPedidos';
        elseif ($documentoNegocio->pago_negocio_id) $activeTab = 'tabPagos';

        $documentoNegocio->delete();

        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Documento eliminado.')
            ->with('activeTab', $activeTab);
    }
}

<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentoCompraRequest;
use App\Http\Requests\UpdateDocumentoCompraRequest;
use App\Models\Compra;
use App\Models\DocumentoCompra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoCompraController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(StoreDocumentoCompraRequest $request, Compra $compra)
    {
        abort_unless($compra->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();
        $data['archivo_path'] = $request->file('archivo')->store('documentos-compra', 'public');
        unset($data['archivo']);
        $data['compra_id'] = $compra->id;
        $data['hogar_id']  = $this->hogarId();

        DocumentoCompra::create($data);

        return redirect()->route('dashboard.compras.show', $compra)
            ->with('success', 'Documento agregado correctamente.');
    }

    public function update(UpdateDocumentoCompraRequest $request, DocumentoCompra $documento)
    {
        abort_unless($documento->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            if ($documento->archivo_path) {
                Storage::disk('public')->delete($documento->archivo_path);
            }
            $data['archivo_path'] = $request->file('archivo')->store('documentos-compra', 'public');
        }
        unset($data['archivo']);

        $documento->update($data);

        return redirect()->route('dashboard.compras.show', $documento->compra_id)
            ->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(DocumentoCompra $documento)
    {
        abort_unless($documento->hogar_id === $this->hogarId(), 403);

        $compraId = $documento->compra_id;

        if ($documento->archivo_path) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $documento->delete();

        return redirect()->route('dashboard.compras.show', $compraId)
            ->with('success', 'Documento eliminado correctamente.');
    }
}

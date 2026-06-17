<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentoFinancieroRequest;
use App\Http\Requests\UpdateDocumentoFinancieroRequest;
use App\Models\DocumentoFinanciero;
use App\Models\ProductoFinanciero;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoFinancieroController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(StoreDocumentoFinancieroRequest $request, ProductoFinanciero $productoFinanciero)
    {
        abort_unless($productoFinanciero->miembro?->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            $uuid = Str::uuid();
            $ext  = $request->file('archivo')->getClientOriginalExtension();
            $data['archivo_path'] = $request->file('archivo')->storeAs(
                "documentos_financieros/{$uuid}", "archivo.{$ext}", 'public'
            );
        }
        unset($data['archivo']);

        $data['hogar_id']              = $this->hogarId();
        $data['producto_financiero_id'] = $productoFinanciero->id;

        DocumentoFinanciero::create($data);

        return redirect()->route('dashboard.productos-financieros.show', $productoFinanciero)
            ->with('success', 'Documento agregado correctamente.');
    }

    public function update(UpdateDocumentoFinancieroRequest $request, DocumentoFinanciero $documentoFinanciero)
    {
        abort_unless($documentoFinanciero->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            if ($documentoFinanciero->archivo_path) {
                Storage::disk('public')->deleteDirectory(dirname($documentoFinanciero->archivo_path));
            }
            $uuid = Str::uuid();
            $ext  = $request->file('archivo')->getClientOriginalExtension();
            $data['archivo_path'] = $request->file('archivo')->storeAs(
                "documentos_financieros/{$uuid}", "archivo.{$ext}", 'public'
            );
        }
        unset($data['archivo']);

        $documentoFinanciero->update($data);

        return redirect()->route('dashboard.productos-financieros.show', $documentoFinanciero->producto_financiero_id)
            ->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(DocumentoFinanciero $documentoFinanciero)
    {
        abort_unless($documentoFinanciero->hogar_id === $this->hogarId(), 403);

        $productoId = $documentoFinanciero->producto_financiero_id;

        if ($documentoFinanciero->archivo_path) {
            Storage::disk('public')->deleteDirectory(dirname($documentoFinanciero->archivo_path));
        }

        $documentoFinanciero->delete();

        return redirect()->route('dashboard.productos-financieros.show', $productoId)
            ->with('success', 'Documento eliminado correctamente.');
    }
}

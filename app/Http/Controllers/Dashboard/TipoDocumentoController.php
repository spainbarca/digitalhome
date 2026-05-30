<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoRequest;
use App\Http\Requests\UpdateTipoDocumentoRequest;
use App\Models\TipoDocumento;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TipoDocumentoController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumento::orderBy('nombre')->get();
        return view('dashboard.tipo-documento.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoRequest $request): JsonResponse
    {
        $tipo = TipoDocumento::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoRequest $request, TipoDocumento $tipoDocumento): JsonResponse
    {
        $tipoDocumento->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumento->fresh()]);
    }

    public function destroy(TipoDocumento $tipoDocumento): JsonResponse
    {
        $enUso = DB::table('personas')->where('tipo_documento_id', $tipoDocumento->id)->exists();

        if ($enUso) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar «{$tipoDocumento->codigo}» porque está siendo usado por personas registradas.",
            ], 422);
        }

        $tipoDocumento->delete();
        return response()->json(['success' => true]);
    }
}

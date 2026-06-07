<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoCompraRequest;
use App\Http\Requests\UpdateTipoDocumentoCompraRequest;
use App\Models\TipoDocumentoCompra;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoCompraController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumentoCompra::orderBy('nombre')->get();
        return view('dashboard.tipo-documento-compra.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoCompraRequest $request): JsonResponse
    {
        $tipo = TipoDocumentoCompra::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoCompraRequest $request, TipoDocumentoCompra $tipoDocumentoCompra): JsonResponse
    {
        $tipoDocumentoCompra->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumentoCompra->fresh()]);
    }

    public function destroy(TipoDocumentoCompra $tipoDocumentoCompra): JsonResponse
    {
        $tipoDocumentoCompra->delete();
        return response()->json(['success' => true]);
    }
}

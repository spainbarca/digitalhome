<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoNegocioRequest;
use App\Http\Requests\UpdateTipoDocumentoNegocioRequest;
use App\Models\TipoDocumentoNegocio;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoNegocioController extends Controller
{
    public function index(): View
    {
        $registros = TipoDocumentoNegocio::orderBy('orden')->orderBy('nombre')->get();
        return view('dashboard.tipo-documento-negocio.index', compact('registros'));
    }

    public function store(StoreTipoDocumentoNegocioRequest $request): JsonResponse
    {
        $registro = TipoDocumentoNegocio::create($request->validated());
        return response()->json(['success' => true, 'data' => $registro]);
    }

    public function update(UpdateTipoDocumentoNegocioRequest $request, TipoDocumentoNegocio $tipoDocumentoNegocio): JsonResponse
    {
        $tipoDocumentoNegocio->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumentoNegocio->fresh()]);
    }

    public function destroy(TipoDocumentoNegocio $tipoDocumentoNegocio): JsonResponse
    {
        $tipoDocumentoNegocio->delete();
        return response()->json(['success' => true]);
    }
}

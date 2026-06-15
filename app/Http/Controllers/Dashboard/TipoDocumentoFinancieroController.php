<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoFinancieroRequest;
use App\Http\Requests\UpdateTipoDocumentoFinancieroRequest;
use App\Models\TipoDocumentoFinanciero;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoFinancieroController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumentoFinanciero::orderBy('nombre')->get();
        return view('dashboard.tipo-documento-financiero.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoFinancieroRequest $request): JsonResponse
    {
        $tipo = TipoDocumentoFinanciero::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoFinancieroRequest $request, TipoDocumentoFinanciero $tipoDocumentoFinanciero): JsonResponse
    {
        $tipoDocumentoFinanciero->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumentoFinanciero->fresh()]);
    }

    public function destroy(TipoDocumentoFinanciero $tipoDocumentoFinanciero): JsonResponse
    {
        $tipoDocumentoFinanciero->delete();
        return response()->json(['success' => true]);
    }
}

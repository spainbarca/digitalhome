<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoProductoFinancieroRequest;
use App\Http\Requests\UpdateTipoProductoFinancieroRequest;
use App\Models\TipoProductoFinanciero;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoProductoFinancieroController extends Controller
{
    public function index(): View
    {
        $tipos = TipoProductoFinanciero::orderBy('nombre')->get();
        return view('dashboard.tipo-producto-financiero.index', compact('tipos'));
    }

    public function store(StoreTipoProductoFinancieroRequest $request): JsonResponse
    {
        $tipo = TipoProductoFinanciero::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoProductoFinancieroRequest $request, TipoProductoFinanciero $tipoProductoFinanciero): JsonResponse
    {
        $tipoProductoFinanciero->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoProductoFinanciero->fresh()]);
    }

    public function destroy(TipoProductoFinanciero $tipoProductoFinanciero): JsonResponse
    {
        $tipoProductoFinanciero->delete();
        return response()->json(['success' => true]);
    }
}

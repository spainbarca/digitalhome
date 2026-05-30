<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoContribuyenteRequest;
use App\Http\Requests\UpdateTipoContribuyenteRequest;
use App\Models\TipoContribuyente;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoContribuyenteController extends Controller
{
    public function index(): View
    {
        $tipos = TipoContribuyente::withCount('empresas')->orderBy('nombre')->get();
        return view('dashboard.tipo-contribuyente.index', compact('tipos'));
    }

    public function store(StoreTipoContribuyenteRequest $request): JsonResponse
    {
        $tipo = TipoContribuyente::create($request->validated());
        $tipo->empresas_count = 0;
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoContribuyenteRequest $request, TipoContribuyente $tipoContribuyente): JsonResponse
    {
        $tipoContribuyente->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoContribuyente->fresh()]);
    }

    public function destroy(TipoContribuyente $tipoContribuyente): JsonResponse
    {
        if ($tipoContribuyente->empresas()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar «{$tipoContribuyente->nombre}» porque tiene empresas asociadas.",
            ], 422);
        }
        $tipoContribuyente->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoEntidadFinancieraRequest;
use App\Http\Requests\UpdateTipoEntidadFinancieraRequest;
use App\Models\TipoEntidadFinanciera;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoEntidadFinancieraController extends Controller
{
    public function index(): View
    {
        $tipos = TipoEntidadFinanciera::orderBy('nombre')->get();
        return view('dashboard.tipo-entidad-financiera.index', compact('tipos'));
    }

    public function store(StoreTipoEntidadFinancieraRequest $request): JsonResponse
    {
        $tipo = TipoEntidadFinanciera::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoEntidadFinancieraRequest $request, TipoEntidadFinanciera $tipoEntidadFinanciera): JsonResponse
    {
        $tipoEntidadFinanciera->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoEntidadFinanciera->fresh()]);
    }

    public function destroy(TipoEntidadFinanciera $tipoEntidadFinanciera): JsonResponse
    {
        $tipoEntidadFinanciera->delete();
        return response()->json(['success' => true]);
    }
}

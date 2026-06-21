<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoTransporteRequest;
use App\Http\Requests\UpdateTipoTransporteRequest;
use App\Models\TipoTransporte;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoTransporteController extends Controller
{
    public function index(): View
    {
        $tipos = TipoTransporte::orderBy('nombre')->get();
        return view('dashboard.tipo-transporte.index', compact('tipos'));
    }

    public function store(StoreTipoTransporteRequest $request): JsonResponse
    {
        $tipo = TipoTransporte::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoTransporteRequest $request, TipoTransporte $tipoTransporte): JsonResponse
    {
        $tipoTransporte->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoTransporte->fresh()]);
    }

    public function destroy(TipoTransporte $tipoTransporte): JsonResponse
    {
        $tipoTransporte->delete();
        return response()->json(['success' => true]);
    }
}

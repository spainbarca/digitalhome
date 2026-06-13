<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoCapacitacionRequest;
use App\Http\Requests\UpdateTipoCapacitacionRequest;
use App\Models\TipoCapacitacion;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoCapacitacionController extends Controller
{
    public function index(): View
    {
        $tipos = TipoCapacitacion::orderBy('nombre')->get();
        return view('dashboard.tipo-capacitacion.index', compact('tipos'));
    }

    public function store(StoreTipoCapacitacionRequest $request): JsonResponse
    {
        $tipo = TipoCapacitacion::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoCapacitacionRequest $request, TipoCapacitacion $tipoCapacitacion): JsonResponse
    {
        $tipoCapacitacion->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoCapacitacion->fresh()]);
    }

    public function destroy(TipoCapacitacion $tipoCapacitacion): JsonResponse
    {
        $tipoCapacitacion->delete();
        return response()->json(['success' => true]);
    }
}

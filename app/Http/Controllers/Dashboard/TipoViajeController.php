<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoViajeRequest;
use App\Http\Requests\UpdateTipoViajeRequest;
use App\Models\TipoViaje;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoViajeController extends Controller
{
    public function index(): View
    {
        $tipos = TipoViaje::orderBy('nombre')->get();
        return view('dashboard.tipo-viaje.index', compact('tipos'));
    }

    public function store(StoreTipoViajeRequest $request): JsonResponse
    {
        $tipo = TipoViaje::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoViajeRequest $request, TipoViaje $tipoViaje): JsonResponse
    {
        $tipoViaje->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoViaje->fresh()]);
    }

    public function destroy(TipoViaje $tipoViaje): JsonResponse
    {
        $tipoViaje->delete();
        return response()->json(['success' => true]);
    }
}

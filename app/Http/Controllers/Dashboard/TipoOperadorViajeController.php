<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoOperadorViajeRequest;
use App\Http\Requests\UpdateTipoOperadorViajeRequest;
use App\Models\TipoOperadorViaje;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoOperadorViajeController extends Controller
{
    public function index(): View
    {
        $tipos = TipoOperadorViaje::orderBy('nombre')->get();
        return view('dashboard.tipo-operador-viaje.index', compact('tipos'));
    }

    public function store(StoreTipoOperadorViajeRequest $request): JsonResponse
    {
        $tipo = TipoOperadorViaje::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoOperadorViajeRequest $request, TipoOperadorViaje $tipoOperadorViaje): JsonResponse
    {
        $tipoOperadorViaje->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoOperadorViaje->fresh()]);
    }

    public function destroy(TipoOperadorViaje $tipoOperadorViaje): JsonResponse
    {
        $tipoOperadorViaje->delete();
        return response()->json(['success' => true]);
    }
}

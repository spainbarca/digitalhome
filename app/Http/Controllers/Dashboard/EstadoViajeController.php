<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoViajeRequest;
use App\Http\Requests\UpdateEstadoViajeRequest;
use App\Models\EstadoViaje;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EstadoViajeController extends Controller
{
    public function index(): View
    {
        $estados = EstadoViaje::orderBy('nombre')->get();
        return view('dashboard.estado-viaje.index', compact('estados'));
    }

    public function store(StoreEstadoViajeRequest $request): JsonResponse
    {
        $estado = EstadoViaje::create($request->validated());
        return response()->json(['success' => true, 'data' => $estado]);
    }

    public function update(UpdateEstadoViajeRequest $request, EstadoViaje $estadoViaje): JsonResponse
    {
        $estadoViaje->update($request->validated());
        return response()->json(['success' => true, 'data' => $estadoViaje->fresh()]);
    }

    public function destroy(EstadoViaje $estadoViaje): JsonResponse
    {
        $estadoViaje->delete();
        return response()->json(['success' => true]);
    }
}

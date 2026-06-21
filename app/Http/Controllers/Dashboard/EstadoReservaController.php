<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoReservaRequest;
use App\Http\Requests\UpdateEstadoReservaRequest;
use App\Models\EstadoReserva;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EstadoReservaController extends Controller
{
    public function index(): View
    {
        $estados = EstadoReserva::orderBy('nombre')->get();
        return view('dashboard.estado-reserva.index', compact('estados'));
    }

    public function store(StoreEstadoReservaRequest $request): JsonResponse
    {
        $estado = EstadoReserva::create($request->validated());
        return response()->json(['success' => true, 'data' => $estado]);
    }

    public function update(UpdateEstadoReservaRequest $request, EstadoReserva $estadoReserva): JsonResponse
    {
        $estadoReserva->update($request->validated());
        return response()->json(['success' => true, 'data' => $estadoReserva->fresh()]);
    }

    public function destroy(EstadoReserva $estadoReserva): JsonResponse
    {
        $estadoReserva->delete();
        return response()->json(['success' => true]);
    }
}

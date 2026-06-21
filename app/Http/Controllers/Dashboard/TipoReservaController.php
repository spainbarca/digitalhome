<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoReservaRequest;
use App\Http\Requests\UpdateTipoReservaRequest;
use App\Models\TipoReserva;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoReservaController extends Controller
{
    public function index(): View
    {
        $tipos = TipoReserva::orderBy('nombre')->get();
        return view('dashboard.tipo-reserva.index', compact('tipos'));
    }

    public function store(StoreTipoReservaRequest $request): JsonResponse
    {
        $tipo = TipoReserva::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoReservaRequest $request, TipoReserva $tipoReserva): JsonResponse
    {
        $tipoReserva->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoReserva->fresh()]);
    }

    public function destroy(TipoReserva $tipoReserva): JsonResponse
    {
        $tipoReserva->delete();
        return response()->json(['success' => true]);
    }
}

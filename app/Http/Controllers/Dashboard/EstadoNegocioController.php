<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoNegocioRequest;
use App\Http\Requests\UpdateEstadoNegocioRequest;
use App\Models\EstadoNegocio;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EstadoNegocioController extends Controller
{
    public function index(): View
    {
        $registros = EstadoNegocio::orderBy('orden')->orderBy('nombre')->get();
        return view('dashboard.estado-negocio.index', compact('registros'));
    }

    public function store(StoreEstadoNegocioRequest $request): JsonResponse
    {
        $registro = EstadoNegocio::create($request->validated());
        return response()->json(['success' => true, 'data' => $registro]);
    }

    public function update(UpdateEstadoNegocioRequest $request, EstadoNegocio $estadoNegocio): JsonResponse
    {
        $estadoNegocio->update($request->validated());
        return response()->json(['success' => true, 'data' => $estadoNegocio->fresh()]);
    }

    public function destroy(EstadoNegocio $estadoNegocio): JsonResponse
    {
        $estadoNegocio->delete();
        return response()->json(['success' => true]);
    }
}

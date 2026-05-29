<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoServicioRequest;
use App\Http\Requests\UpdateTipoServicioRequest;
use App\Models\TipoServicio;
use App\Models\UnidadMedida;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoServicioController extends Controller
{
    public function index(): View
    {
        $tipos    = TipoServicio::with('unidadMedida')->orderBy('nombre')->get();
        $unidades = UnidadMedida::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.tipos-servicio.index', compact('tipos', 'unidades'));
    }

    public function store(StoreTipoServicioRequest $request): JsonResponse
    {
        $tipo = TipoServicio::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo->load('unidadMedida')]);
    }

    public function update(UpdateTipoServicioRequest $request, TipoServicio $tipo): JsonResponse
    {
        $tipo->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipo->fresh()->load('unidadMedida')]);
    }

    public function destroy(TipoServicio $tipo): JsonResponse
    {
        $tipo->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnidadMedidaRequest;
use App\Http\Requests\UpdateUnidadMedidaRequest;
use App\Models\UnidadMedida;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UnidadMedidaController extends Controller
{
    public function index(): View
    {
        $unidades = UnidadMedida::orderBy('nombre')->get();
        return view('dashboard.unidades-medida.index', compact('unidades'));
    }

    public function store(StoreUnidadMedidaRequest $request): JsonResponse
    {
        $unidad = UnidadMedida::create($request->validated());
        return response()->json(['success' => true, 'data' => $unidad]);
    }

    public function update(UpdateUnidadMedidaRequest $request, UnidadMedida $unidad): JsonResponse
    {
        $unidad->update($request->validated());
        return response()->json(['success' => true, 'data' => $unidad->fresh()]);
    }

    public function destroy(UnidadMedida $unidad): JsonResponse
    {
        $unidad->delete();
        return response()->json(['success' => true]);
    }
}

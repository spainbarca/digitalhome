<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoProductoRequest;
use App\Http\Requests\UpdateEstadoProductoRequest;
use App\Models\EstadoProducto;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EstadoProductoController extends Controller
{
    public function index(): View
    {
        $estados = EstadoProducto::orderBy('nombre')->get();
        return view('dashboard.estado-producto.index', compact('estados'));
    }

    public function store(StoreEstadoProductoRequest $request): JsonResponse
    {
        $estado = EstadoProducto::create($request->validated());
        return response()->json(['success' => true, 'data' => $estado]);
    }

    public function update(UpdateEstadoProductoRequest $request, EstadoProducto $estadoProducto): JsonResponse
    {
        $estadoProducto->update($request->validated());
        return response()->json(['success' => true, 'data' => $estadoProducto->fresh()]);
    }

    public function destroy(EstadoProducto $estadoProducto): JsonResponse
    {
        $estadoProducto->delete();
        return response()->json(['success' => true]);
    }
}

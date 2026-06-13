<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoEmpleoRequest;
use App\Http\Requests\UpdateEstadoEmpleoRequest;
use App\Models\EstadoEmpleo;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EstadoEmpleoController extends Controller
{
    public function index(): View
    {
        $estados = EstadoEmpleo::orderBy('nombre')->get();
        return view('dashboard.estado-empleo.index', compact('estados'));
    }

    public function store(StoreEstadoEmpleoRequest $request): JsonResponse
    {
        $estado = EstadoEmpleo::create($request->validated());
        return response()->json(['success' => true, 'data' => $estado]);
    }

    public function update(UpdateEstadoEmpleoRequest $request, EstadoEmpleo $estadoEmpleo): JsonResponse
    {
        $estadoEmpleo->update($request->validated());
        return response()->json(['success' => true, 'data' => $estadoEmpleo->fresh()]);
    }

    public function destroy(EstadoEmpleo $estadoEmpleo): JsonResponse
    {
        $estadoEmpleo->delete();
        return response()->json(['success' => true]);
    }
}

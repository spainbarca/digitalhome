<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoMatriculaRequest;
use App\Http\Requests\UpdateEstadoMatriculaRequest;
use App\Models\EstadoMatricula;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EstadoMatriculaController extends Controller
{
    public function index(): View
    {
        $estados = EstadoMatricula::orderBy('nombre')->get();
        return view('dashboard.estados-matricula.index', compact('estados'));
    }

    public function store(StoreEstadoMatriculaRequest $request): JsonResponse
    {
        $estado = EstadoMatricula::create($request->validated());
        return response()->json(['success' => true, 'data' => $estado]);
    }

    public function update(UpdateEstadoMatriculaRequest $request, EstadoMatricula $estado): JsonResponse
    {
        $estado->update($request->validated());
        return response()->json(['success' => true, 'data' => $estado->fresh()]);
    }

    public function destroy(EstadoMatricula $estado): JsonResponse
    {
        $estado->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoEntidadLegalRequest;
use App\Http\Requests\UpdateTipoEntidadLegalRequest;
use App\Models\TipoEntidadLegal;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoEntidadLegalController extends Controller
{
    public function index(): View
    {
        $tipos = TipoEntidadLegal::orderBy('nombre')->get();
        return view('dashboard.tipo-entidad-legal.index', compact('tipos'));
    }

    public function store(StoreTipoEntidadLegalRequest $request): JsonResponse
    {
        $tipo = TipoEntidadLegal::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoEntidadLegalRequest $request, TipoEntidadLegal $tipoEntidadLegal): JsonResponse
    {
        $tipoEntidadLegal->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoEntidadLegal->fresh()]);
    }

    public function destroy(TipoEntidadLegal $tipoEntidadLegal): JsonResponse
    {
        $tipoEntidadLegal->delete();
        return response()->json(['success' => true]);
    }
}

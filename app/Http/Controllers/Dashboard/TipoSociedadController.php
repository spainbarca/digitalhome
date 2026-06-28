<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoSociedadRequest;
use App\Http\Requests\UpdateTipoSociedadRequest;
use App\Models\TipoSociedad;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoSociedadController extends Controller
{
    public function index(): View
    {
        $registros = TipoSociedad::orderBy('orden')->orderBy('nombre')->get();
        return view('dashboard.tipo-sociedad.index', compact('registros'));
    }

    public function store(StoreTipoSociedadRequest $request): JsonResponse
    {
        $registro = TipoSociedad::create($request->validated());
        return response()->json(['success' => true, 'data' => $registro]);
    }

    public function update(UpdateTipoSociedadRequest $request, TipoSociedad $tipoSociedad): JsonResponse
    {
        $tipoSociedad->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoSociedad->fresh()]);
    }

    public function destroy(TipoSociedad $tipoSociedad): JsonResponse
    {
        $tipoSociedad->delete();
        return response()->json(['success' => true]);
    }
}

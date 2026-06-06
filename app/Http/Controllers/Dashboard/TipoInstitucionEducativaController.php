<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoInstitucionEducativaRequest;
use App\Http\Requests\UpdateTipoInstitucionEducativaRequest;
use App\Models\TipoInstitucionEducativa;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoInstitucionEducativaController extends Controller
{
    public function index(): View
    {
        $tipos = TipoInstitucionEducativa::orderBy('nombre')->get();
        return view('dashboard.tipo-institucion-educativa.index', compact('tipos'));
    }

    public function store(StoreTipoInstitucionEducativaRequest $request): JsonResponse
    {
        $tipo = TipoInstitucionEducativa::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoInstitucionEducativaRequest $request, TipoInstitucionEducativa $tipo): JsonResponse
    {
        $tipo->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipo->fresh()]);
    }

    public function destroy(TipoInstitucionEducativa $tipo): JsonResponse
    {
        $tipo->delete();
        return response()->json(['success' => true]);
    }
}

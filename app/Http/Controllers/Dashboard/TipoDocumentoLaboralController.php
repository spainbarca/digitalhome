<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoLaboralRequest;
use App\Http\Requests\UpdateTipoDocumentoLaboralRequest;
use App\Models\TipoDocumentoLaboral;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoLaboralController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumentoLaboral::orderBy('nombre')->get();
        return view('dashboard.tipo-documento-laboral.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoLaboralRequest $request): JsonResponse
    {
        $tipo = TipoDocumentoLaboral::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoLaboralRequest $request, TipoDocumentoLaboral $tipoDocumentoLaboral): JsonResponse
    {
        $tipoDocumentoLaboral->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumentoLaboral->fresh()]);
    }

    public function destroy(TipoDocumentoLaboral $tipoDocumentoLaboral): JsonResponse
    {
        $tipoDocumentoLaboral->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoMedicoRequest;
use App\Http\Requests\UpdateTipoDocumentoMedicoRequest;
use App\Models\TipoDocumentoMedico;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoMedicoController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumentoMedico::orderBy('nombre')->get();
        return view('dashboard.tipos-documento-medico.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoMedicoRequest $request): JsonResponse
    {
        $tipo = TipoDocumentoMedico::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoMedicoRequest $request, TipoDocumentoMedico $tipoDocumentoMedico): JsonResponse
    {
        $tipoDocumentoMedico->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumentoMedico->fresh()]);
    }

    public function destroy(TipoDocumentoMedico $tipoDocumentoMedico): JsonResponse
    {
        $tipoDocumentoMedico->delete();
        return response()->json(['success' => true]);
    }
}

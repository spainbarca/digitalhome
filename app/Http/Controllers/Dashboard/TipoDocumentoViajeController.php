<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoViajeRequest;
use App\Http\Requests\UpdateTipoDocumentoViajeRequest;
use App\Models\TipoDocumentoViaje;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoViajeController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumentoViaje::orderBy('nombre')->get();
        return view('dashboard.tipo-documento-viaje.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoViajeRequest $request): JsonResponse
    {
        $tipo = TipoDocumentoViaje::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoViajeRequest $request, TipoDocumentoViaje $tipoDocumentoViaje): JsonResponse
    {
        $tipoDocumentoViaje->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumentoViaje->fresh()]);
    }

    public function destroy(TipoDocumentoViaje $tipoDocumentoViaje): JsonResponse
    {
        $tipoDocumentoViaje->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoEducativoRequest;
use App\Http\Requests\UpdateTipoDocumentoEducativoRequest;
use App\Models\TipoDocumentoEducativo;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoEducativoController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumentoEducativo::orderBy('nombre')->get();
        return view('dashboard.tipo-documento-educativo.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoEducativoRequest $request): JsonResponse
    {
        $tipo = TipoDocumentoEducativo::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoEducativoRequest $request, TipoDocumentoEducativo $tipo): JsonResponse
    {
        $tipo->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipo->fresh()]);
    }

    public function destroy(TipoDocumentoEducativo $tipo): JsonResponse
    {
        $tipo->delete();
        return response()->json(['success' => true]);
    }
}

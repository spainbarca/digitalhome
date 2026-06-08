<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoDocumentoLegalRequest;
use App\Http\Requests\UpdateTipoDocumentoLegalRequest;
use App\Models\TipoDocumentoLegal;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoDocumentoLegalController extends Controller
{
    public function index(): View
    {
        $tipos = TipoDocumentoLegal::orderBy('categoria')->orderBy('nombre')->get();
        return view('dashboard.tipo-documento-legal.index', compact('tipos'));
    }

    public function store(StoreTipoDocumentoLegalRequest $request): JsonResponse
    {
        $tipo = TipoDocumentoLegal::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoDocumentoLegalRequest $request, TipoDocumentoLegal $tipoDocumentoLegal): JsonResponse
    {
        $tipoDocumentoLegal->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoDocumentoLegal->fresh()]);
    }

    public function destroy(TipoDocumentoLegal $tipoDocumentoLegal): JsonResponse
    {
        $tipoDocumentoLegal->delete();
        return response()->json(['success' => true]);
    }
}

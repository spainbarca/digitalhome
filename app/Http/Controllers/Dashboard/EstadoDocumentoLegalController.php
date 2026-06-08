<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstadoDocumentoLegalRequest;
use App\Http\Requests\UpdateEstadoDocumentoLegalRequest;
use App\Models\EstadoDocumentoLegal;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EstadoDocumentoLegalController extends Controller
{
    public function index(): View
    {
        $estados = EstadoDocumentoLegal::orderBy('nombre')->get();
        return view('dashboard.estado-documento-legal.index', compact('estados'));
    }

    public function store(StoreEstadoDocumentoLegalRequest $request): JsonResponse
    {
        $estado = EstadoDocumentoLegal::create($request->validated());
        return response()->json(['success' => true, 'data' => $estado]);
    }

    public function update(UpdateEstadoDocumentoLegalRequest $request, EstadoDocumentoLegal $estadoDocumentoLegal): JsonResponse
    {
        $estadoDocumentoLegal->update($request->validated());
        return response()->json(['success' => true, 'data' => $estadoDocumentoLegal->fresh()]);
    }

    public function destroy(EstadoDocumentoLegal $estadoDocumentoLegal): JsonResponse
    {
        $estadoDocumentoLegal->delete();
        return response()->json(['success' => true]);
    }
}

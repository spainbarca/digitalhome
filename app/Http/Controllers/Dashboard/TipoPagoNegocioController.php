<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoPagoNegocioRequest;
use App\Http\Requests\UpdateTipoPagoNegocioRequest;
use App\Models\TipoPagoNegocio;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoPagoNegocioController extends Controller
{
    public function index(): View
    {
        $registros = TipoPagoNegocio::orderBy('orden')->orderBy('nombre')->get();
        return view('dashboard.tipo-pago-negocio.index', compact('registros'));
    }

    public function store(StoreTipoPagoNegocioRequest $request): JsonResponse
    {
        $registro = TipoPagoNegocio::create($request->validated());
        return response()->json(['success' => true, 'data' => $registro]);
    }

    public function update(UpdateTipoPagoNegocioRequest $request, TipoPagoNegocio $tipoPagoNegocio): JsonResponse
    {
        $tipoPagoNegocio->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoPagoNegocio->fresh()]);
    }

    public function destroy(TipoPagoNegocio $tipoPagoNegocio): JsonResponse
    {
        $tipoPagoNegocio->delete();
        return response()->json(['success' => true]);
    }
}

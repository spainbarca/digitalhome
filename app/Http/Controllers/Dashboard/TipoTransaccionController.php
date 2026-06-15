<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoTransaccionRequest;
use App\Http\Requests\UpdateTipoTransaccionRequest;
use App\Models\TipoTransaccion;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoTransaccionController extends Controller
{
    public function index(): View
    {
        $tipos = TipoTransaccion::orderBy('nombre')->get();
        return view('dashboard.tipo-transaccion.index', compact('tipos'));
    }

    public function store(StoreTipoTransaccionRequest $request): JsonResponse
    {
        $tipo = TipoTransaccion::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoTransaccionRequest $request, TipoTransaccion $tipoTransaccion): JsonResponse
    {
        $tipoTransaccion->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoTransaccion->fresh()]);
    }

    public function destroy(TipoTransaccion $tipoTransaccion): JsonResponse
    {
        $tipoTransaccion->delete();
        return response()->json(['success' => true]);
    }
}

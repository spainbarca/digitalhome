<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoComercioRequest;
use App\Http\Requests\UpdateTipoComercioRequest;
use App\Models\TipoComercio;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoComercioController extends Controller
{
    public function index(): View
    {
        $tipos = TipoComercio::orderBy('nombre')->get();
        return view('dashboard.tipo-comercio.index', compact('tipos'));
    }

    public function store(StoreTipoComercioRequest $request): JsonResponse
    {
        $tipo = TipoComercio::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoComercioRequest $request, TipoComercio $tipoComercio): JsonResponse
    {
        $tipoComercio->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoComercio->fresh()]);
    }

    public function destroy(TipoComercio $tipoComercio): JsonResponse
    {
        $tipoComercio->delete();
        return response()->json(['success' => true]);
    }
}

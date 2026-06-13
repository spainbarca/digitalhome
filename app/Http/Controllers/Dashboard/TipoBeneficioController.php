<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoBeneficioRequest;
use App\Http\Requests\UpdateTipoBeneficioRequest;
use App\Models\TipoBeneficio;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoBeneficioController extends Controller
{
    public function index(): View
    {
        $tipos = TipoBeneficio::orderBy('nombre')->get();
        return view('dashboard.tipo-beneficio.index', compact('tipos'));
    }

    public function store(StoreTipoBeneficioRequest $request): JsonResponse
    {
        $tipo = TipoBeneficio::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoBeneficioRequest $request, TipoBeneficio $tipoBeneficio): JsonResponse
    {
        $tipoBeneficio->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoBeneficio->fresh()]);
    }

    public function destroy(TipoBeneficio $tipoBeneficio): JsonResponse
    {
        $tipoBeneficio->delete();
        return response()->json(['success' => true]);
    }
}

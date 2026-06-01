<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoCentroMedicoRequest;
use App\Http\Requests\UpdateTipoCentroMedicoRequest;
use App\Models\TipoCentroMedico;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoCentroMedicoController extends Controller
{
    public function index(): View
    {
        $tipos = TipoCentroMedico::orderBy('nombre')->get();
        return view('dashboard.tipos-centro-medico.index', compact('tipos'));
    }

    public function store(StoreTipoCentroMedicoRequest $request): JsonResponse
    {
        $tipo = TipoCentroMedico::create($request->validated());
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoCentroMedicoRequest $request, TipoCentroMedico $tipoCentroMedico): JsonResponse
    {
        $tipoCentroMedico->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipoCentroMedico->fresh()]);
    }

    public function destroy(TipoCentroMedico $tipoCentroMedico): JsonResponse
    {
        $tipoCentroMedico->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEspecialidadMedicaRequest;
use App\Http\Requests\UpdateEspecialidadMedicaRequest;
use App\Models\EspecialidadMedica;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EspecialidadMedicaController extends Controller
{
    public function index(): View
    {
        $especialidades = EspecialidadMedica::orderBy('nombre')->get();
        return view('dashboard.especialidades-medicas.index', compact('especialidades'));
    }

    public function store(StoreEspecialidadMedicaRequest $request): JsonResponse
    {
        $especialidad = EspecialidadMedica::create($request->validated());
        return response()->json(['success' => true, 'data' => $especialidad]);
    }

    public function update(UpdateEspecialidadMedicaRequest $request, EspecialidadMedica $especialidadMedica): JsonResponse
    {
        $especialidadMedica->update($request->validated());
        return response()->json(['success' => true, 'data' => $especialidadMedica->fresh()]);
    }

    public function destroy(EspecialidadMedica $especialidadMedica): JsonResponse
    {
        $especialidadMedica->delete();
        return response()->json(['success' => true]);
    }
}

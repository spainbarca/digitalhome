<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTurnoEducativoRequest;
use App\Http\Requests\UpdateTurnoEducativoRequest;
use App\Models\TurnoEducativo;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TurnoEducativoController extends Controller
{
    public function index(): View
    {
        $turnos = TurnoEducativo::orderBy('nombre')->get();
        return view('dashboard.turnos-educativos.index', compact('turnos'));
    }

    public function store(StoreTurnoEducativoRequest $request): JsonResponse
    {
        $turno = TurnoEducativo::create($request->validated());
        return response()->json(['success' => true, 'data' => $turno]);
    }

    public function update(UpdateTurnoEducativoRequest $request, TurnoEducativo $turno): JsonResponse
    {
        $turno->update($request->validated());
        return response()->json(['success' => true, 'data' => $turno->fresh()]);
    }

    public function destroy(TurnoEducativo $turno): JsonResponse
    {
        $turno->delete();
        return response()->json(['success' => true]);
    }
}

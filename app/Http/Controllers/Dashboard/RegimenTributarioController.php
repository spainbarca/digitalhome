<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegimenTributarioRequest;
use App\Http\Requests\UpdateRegimenTributarioRequest;
use App\Models\RegimenTributario;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RegimenTributarioController extends Controller
{
    public function index(): View
    {
        $registros = RegimenTributario::orderBy('orden')->orderBy('nombre')->get();
        return view('dashboard.regimen-tributario.index', compact('registros'));
    }

    public function store(StoreRegimenTributarioRequest $request): JsonResponse
    {
        $registro = RegimenTributario::create($request->validated());
        return response()->json(['success' => true, 'data' => $registro]);
    }

    public function update(UpdateRegimenTributarioRequest $request, RegimenTributario $regimenTributario): JsonResponse
    {
        $regimenTributario->update($request->validated());
        return response()->json(['success' => true, 'data' => $regimenTributario->fresh()]);
    }

    public function destroy(RegimenTributario $regimenTributario): JsonResponse
    {
        $regimenTributario->delete();
        return response()->json(['success' => true]);
    }
}

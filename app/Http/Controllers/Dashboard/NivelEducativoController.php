<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNivelEducativoRequest;
use App\Http\Requests\UpdateNivelEducativoRequest;
use App\Models\NivelEducativo;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NivelEducativoController extends Controller
{
    public function index(): View
    {
        $niveles = NivelEducativo::orderBy('orden')->get();
        return view('dashboard.niveles-educativos.index', compact('niveles'));
    }

    public function store(StoreNivelEducativoRequest $request): JsonResponse
    {
        $nivel = NivelEducativo::create($request->validated());
        return response()->json(['success' => true, 'data' => $nivel]);
    }

    public function update(UpdateNivelEducativoRequest $request, NivelEducativo $nivel): JsonResponse
    {
        $nivel->update($request->validated());
        return response()->json(['success' => true, 'data' => $nivel->fresh()]);
    }

    public function destroy(NivelEducativo $nivel): JsonResponse
    {
        $nivel->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriaGastoViajeRequest;
use App\Http\Requests\UpdateCategoriaGastoViajeRequest;
use App\Models\CategoriaGastoViaje;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoriaGastoViajeController extends Controller
{
    public function index(): View
    {
        $categorias = CategoriaGastoViaje::orderBy('nombre')->get();
        return view('dashboard.categoria-gasto-viaje.index', compact('categorias'));
    }

    public function store(StoreCategoriaGastoViajeRequest $request): JsonResponse
    {
        $categoria = CategoriaGastoViaje::create($request->validated());
        return response()->json(['success' => true, 'data' => $categoria]);
    }

    public function update(UpdateCategoriaGastoViajeRequest $request, CategoriaGastoViaje $categoriaGastoViaje): JsonResponse
    {
        $categoriaGastoViaje->update($request->validated());
        return response()->json(['success' => true, 'data' => $categoriaGastoViaje->fresh()]);
    }

    public function destroy(CategoriaGastoViaje $categoriaGastoViaje): JsonResponse
    {
        $categoriaGastoViaje->delete();
        return response()->json(['success' => true]);
    }
}

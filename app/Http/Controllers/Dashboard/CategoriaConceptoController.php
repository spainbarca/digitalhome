<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriaConceptoRequest;
use App\Http\Requests\UpdateCategoriaConceptoRequest;
use App\Models\CategoriaConcepto;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoriaConceptoController extends Controller
{
    public function index(): View
    {
        $categorias = CategoriaConcepto::orderBy('nombre')->get();
        return view('dashboard.categorias-concepto.index', compact('categorias'));
    }

    public function store(StoreCategoriaConceptoRequest $request): JsonResponse
    {
        $categoria = CategoriaConcepto::create($request->validated());
        return response()->json(['success' => true, 'data' => $categoria]);
    }

    public function update(UpdateCategoriaConceptoRequest $request, CategoriaConcepto $categoriaConcepto): JsonResponse
    {
        $categoriaConcepto->update($request->validated());
        return response()->json(['success' => true, 'data' => $categoriaConcepto->fresh()]);
    }

    public function destroy(CategoriaConcepto $categoriaConcepto): JsonResponse
    {
        $categoriaConcepto->delete();
        return response()->json(['success' => true]);
    }
}

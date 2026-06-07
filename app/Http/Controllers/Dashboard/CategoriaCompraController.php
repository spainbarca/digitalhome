<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoriaCompraRequest;
use App\Http\Requests\UpdateCategoriaCompraRequest;
use App\Models\CategoriaCompra;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoriaCompraController extends Controller
{
    public function index(): View
    {
        $categorias = CategoriaCompra::orderBy('nombre')->get();
        return view('dashboard.categorias-compra.index', compact('categorias'));
    }

    public function store(StoreCategoriaCompraRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen_path'] = $request->file('imagen')->store('categorias-compra', 'public');
        }

        unset($data['imagen']);
        $categoria = CategoriaCompra::create($data);

        return response()->json(['success' => true, 'data' => $categoria]);
    }

    public function update(UpdateCategoriaCompraRequest $request, CategoriaCompra $categoria): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($categoria->imagen_path) {
                Storage::disk('public')->delete($categoria->imagen_path);
            }
            $data['imagen_path'] = $request->file('imagen')->store('categorias-compra', 'public');
        }

        unset($data['imagen']);
        $categoria->update($data);

        return response()->json(['success' => true, 'data' => $categoria->fresh()]);
    }

    public function destroy(CategoriaCompra $categoria): JsonResponse
    {
        $categoria->delete();
        return response()->json(['success' => true]);
    }
}

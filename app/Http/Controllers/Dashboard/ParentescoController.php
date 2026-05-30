<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParentescoRequest;
use App\Http\Requests\UpdateParentescoRequest;
use App\Models\Parentesco;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ParentescoController extends Controller
{
    public function index(): View
    {
        $parentescos = Parentesco::withCount('hogarMiembros')->orderBy('grupo')->orderBy('nombre')->get();
        return view('dashboard.parentesco.index', compact('parentescos'));
    }

    public function store(StoreParentescoRequest $request): JsonResponse
    {
        $parentesco = Parentesco::create($request->validated());
        $parentesco->hogar_miembros_count = 0;
        return response()->json(['success' => true, 'data' => $parentesco]);
    }

    public function update(UpdateParentescoRequest $request, Parentesco $parentesco): JsonResponse
    {
        $parentesco->update($request->validated());
        return response()->json(['success' => true, 'data' => $parentesco->fresh()]);
    }

    public function destroy(Parentesco $parentesco): JsonResponse
    {
        if ($parentesco->hogarMiembros()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar «{$parentesco->nombre}» porque está siendo usado por miembros del hogar.",
            ], 422);
        }
        $parentesco->delete();
        return response()->json(['success' => true]);
    }
}

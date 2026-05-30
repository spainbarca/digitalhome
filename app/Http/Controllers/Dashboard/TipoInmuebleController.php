<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoInmuebleRequest;
use App\Http\Requests\UpdateTipoInmuebleRequest;
use App\Models\TipoInmueble;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TipoInmuebleController extends Controller
{
    public function index(): View
    {
        $tipos = TipoInmueble::withCount('propiedadesInmueble')->orderBy('nombre')->get();
        return view('dashboard.tipo-inmueble.index', compact('tipos'));
    }

    public function store(StoreTipoInmuebleRequest $request): JsonResponse
    {
        $tipo = TipoInmueble::create($request->validated());
        $tipo->propiedades_inmueble_count = 0;
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(UpdateTipoInmuebleRequest $request, TipoInmueble $tipo): JsonResponse
    {
        $tipo->update($request->validated());
        return response()->json(['success' => true, 'data' => $tipo->fresh()]);
    }

    public function destroy(TipoInmueble $tipo): JsonResponse
    {
        if ($tipo->propiedadesInmueble()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar «{$tipo->nombre}» porque tiene propiedades asociadas.",
            ], 422);
        }
        $tipo->delete();
        return response()->json(['success' => true]);
    }
}

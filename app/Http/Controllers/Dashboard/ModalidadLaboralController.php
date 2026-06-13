<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModalidadLaboralRequest;
use App\Http\Requests\UpdateModalidadLaboralRequest;
use App\Models\ModalidadLaboral;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ModalidadLaboralController extends Controller
{
    public function index(): View
    {
        $modalidades = ModalidadLaboral::orderBy('nombre')->get();
        return view('dashboard.modalidad-laboral.index', compact('modalidades'));
    }

    public function store(StoreModalidadLaboralRequest $request): JsonResponse
    {
        $modalidad = ModalidadLaboral::create($request->validated());
        return response()->json(['success' => true, 'data' => $modalidad]);
    }

    public function update(UpdateModalidadLaboralRequest $request, ModalidadLaboral $modalidadLaboral): JsonResponse
    {
        $modalidadLaboral->update($request->validated());
        return response()->json(['success' => true, 'data' => $modalidadLaboral->fresh()]);
    }

    public function destroy(ModalidadLaboral $modalidadLaboral): JsonResponse
    {
        $modalidadLaboral->delete();
        return response()->json(['success' => true]);
    }
}

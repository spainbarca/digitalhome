<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetodoPagoRequest;
use App\Http\Requests\UpdateMetodoPagoRequest;
use App\Models\MetodoPago;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MetodoPagoController extends Controller
{
    public function index(): View
    {
        $metodos = MetodoPago::orderBy('nombre')->get();
        return view('dashboard.metodos-pago.index', compact('metodos'));
    }

    public function store(StoreMetodoPagoRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('metodo-pago', 'public');
        }

        $metodo = MetodoPago::create($data);
        return response()->json(['success' => true, 'data' => $this->formato($metodo)]);
    }

    public function update(UpdateMetodoPagoRequest $request, MetodoPago $metodo): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($metodo->logo) {
                Storage::disk('public')->delete($metodo->logo);
            }
            $data['logo'] = $request->file('logo')->store('metodo-pago', 'public');
        }

        $metodo->update($data);
        return response()->json(['success' => true, 'data' => $this->formato($metodo->fresh())]);
    }

    public function destroy(MetodoPago $metodo): JsonResponse
    {
        if ($metodo->logo) {
            Storage::disk('public')->delete($metodo->logo);
        }
        $metodo->delete();
        return response()->json(['success' => true]);
    }

    private function formato(MetodoPago $metodo): array
    {
        return [
            'id'      => $metodo->id,
            'nombre'  => $metodo->nombre,
            'icono'   => $metodo->icono,
            'logo'    => $metodo->logo ? asset('storage/' . $metodo->logo) : null,
            'activo'  => $metodo->activo,
        ];
    }
}

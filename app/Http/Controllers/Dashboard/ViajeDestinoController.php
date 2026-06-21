<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use App\Models\ViajeDestino;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViajeDestinoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Viaje $viaje): RedirectResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'nombre'         => ['required', 'string', 'max:255'],
            'ubicacion_tipo' => ['nullable', 'string', 'in:peru,extranjero'],
            'distrito_inei'  => ['nullable', 'string', 'exists:ubigeo_distritos,inei'],
            'pais_iso2'      => ['nullable', 'string', 'max:2'],
            'ciudad_id'      => ['nullable', 'integer', 'exists:ciudades,id'],
            'fecha_llegada'  => ['nullable', 'date'],
            'fecha_salida'   => ['nullable', 'date', 'after_or_equal:fecha_llegada'],
            'orden'          => ['nullable', 'integer', 'min:0'],
            'notas'          => ['nullable', 'string'],
        ]);

        if (($data['ubicacion_tipo'] ?? null) === 'peru') {
            $data['pais_iso2'] = null;
            $data['ciudad_id'] = null;
        } else {
            $data['distrito_inei'] = null;
        }
        unset($data['ubicacion_tipo']);

        $data['viaje_id'] = $viaje->id;
        ViajeDestino::create($data);

        return redirect()->route('dashboard.viajes.show', $viaje)
            ->with('success', 'Destino agregado correctamente.')
            ->with('activeTab', 'tabDestinos');
    }

    public function update(Request $request, ViajeDestino $viajeDestino): RedirectResponse
    {
        abort_unless($viajeDestino->viaje?->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'nombre'         => ['required', 'string', 'max:255'],
            'ubicacion_tipo' => ['nullable', 'string', 'in:peru,extranjero'],
            'distrito_inei'  => ['nullable', 'string', 'exists:ubigeo_distritos,inei'],
            'pais_iso2'      => ['nullable', 'string', 'max:2'],
            'ciudad_id'      => ['nullable', 'integer', 'exists:ciudades,id'],
            'fecha_llegada'  => ['nullable', 'date'],
            'fecha_salida'   => ['nullable', 'date', 'after_or_equal:fecha_llegada'],
            'orden'          => ['nullable', 'integer', 'min:0'],
            'notas'          => ['nullable', 'string'],
        ]);

        if (($data['ubicacion_tipo'] ?? null) === 'peru') {
            $data['pais_iso2'] = null;
            $data['ciudad_id'] = null;
        } else {
            $data['distrito_inei'] = null;
        }
        unset($data['ubicacion_tipo']);

        $viajeDestino->update($data);

        return redirect()->route('dashboard.viajes.show', $viajeDestino->viaje_id)
            ->with('success', 'Destino actualizado correctamente.')
            ->with('activeTab', 'tabDestinos');
    }

    public function destroy(ViajeDestino $viajeDestino): RedirectResponse
    {
        abort_unless($viajeDestino->viaje?->hogar_id === $this->hogarId(), 403);

        $viajeId = $viajeDestino->viaje_id;
        $viajeDestino->delete();

        return redirect()->route('dashboard.viajes.show', $viajeId)
            ->with('success', 'Destino eliminado correctamente.')
            ->with('activeTab', 'tabDestinos');
    }
}

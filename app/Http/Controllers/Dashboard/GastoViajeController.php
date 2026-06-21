<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GastoViaje;
use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoViajeController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Viaje $viaje): RedirectResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'categoria_gasto_viaje_id' => ['nullable', 'exists:categoria_gasto_viaje,id'],
            'hogar_miembro_id'         => ['nullable', 'exists:hogar_miembros,id'],
            'descripcion'              => ['required', 'string', 'max:255'],
            'monto'                    => ['required', 'numeric', 'min:0.01'],
            'moneda_id'                => ['required', 'exists:monedas,id'],
            'fecha'                    => ['required', 'date'],
            'notas'                    => ['nullable', 'string'],
        ]);

        $data['viaje_id'] = $viaje->id;
        GastoViaje::create($data);

        return redirect()->route('dashboard.viajes.show', $viaje)
            ->with('success', 'Gasto registrado correctamente.')
            ->with('activeTab', 'tabGastos');
    }

    public function update(Request $request, GastoViaje $gastoViaje): RedirectResponse
    {
        abort_unless($gastoViaje->viaje?->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'categoria_gasto_viaje_id' => ['nullable', 'exists:categoria_gasto_viaje,id'],
            'hogar_miembro_id'         => ['nullable', 'exists:hogar_miembros,id'],
            'descripcion'              => ['required', 'string', 'max:255'],
            'monto'                    => ['required', 'numeric', 'min:0.01'],
            'moneda_id'                => ['required', 'exists:monedas,id'],
            'fecha'                    => ['required', 'date'],
            'notas'                    => ['nullable', 'string'],
        ]);

        $gastoViaje->update($data);

        return redirect()->route('dashboard.viajes.show', $gastoViaje->viaje_id)
            ->with('success', 'Gasto actualizado correctamente.')
            ->with('activeTab', 'tabGastos');
    }

    public function destroy(GastoViaje $gastoViaje): RedirectResponse
    {
        abort_unless($gastoViaje->viaje?->hogar_id === $this->hogarId(), 403);

        $viajeId = $gastoViaje->viaje_id;
        $gastoViaje->delete();

        return redirect()->route('dashboard.viajes.show', $viajeId)
            ->with('success', 'Gasto eliminado correctamente.')
            ->with('activeTab', 'tabGastos');
    }
}

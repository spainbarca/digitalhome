<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaViajeController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Viaje $viaje): RedirectResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'tipo_reserva_id'    => ['required', 'exists:tipo_reserva,id'],
            'operador_viaje_id'  => ['nullable', 'exists:operadores_viaje,id'],
            'tipo_transporte_id' => ['nullable', 'exists:tipo_transporte,id'],
            'estado_reserva_id'  => ['nullable', 'exists:estado_reserva,id'],
            'titulo'             => ['nullable', 'string', 'max:255'],
            'codigo_reserva'     => ['nullable', 'string', 'max:255'],
            'fecha_inicio'       => ['nullable', 'date'],
            'fecha_fin'          => ['nullable', 'date'],
            'monto'              => ['nullable', 'numeric', 'min:0'],
            'moneda_id'          => ['nullable', 'exists:monedas,id'],
            'origen'             => ['nullable', 'string', 'max:255'],
            'destino'            => ['nullable', 'string', 'max:255'],
            'numero_servicio'    => ['nullable', 'string', 'max:100'],
            'asiento'            => ['nullable', 'string', 'max:100'],
            'num_noches'         => ['nullable', 'integer', 'min:1'],
            'tipo_habitacion'    => ['nullable', 'string', 'max:100'],
            'direccion'          => ['nullable', 'string'],
            'notas'              => ['nullable', 'string'],
        ]);

        $data['viaje_id'] = $viaje->id;
        Reserva::create($data);

        return redirect()->route('dashboard.viajes.show', $viaje)
            ->with('success', 'Reserva agregada correctamente.')
            ->with('activeTab', 'tabReservas');
    }

    public function update(Request $request, Reserva $reserva): RedirectResponse
    {
        abort_unless($reserva->viaje?->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'tipo_reserva_id'    => ['required', 'exists:tipo_reserva,id'],
            'operador_viaje_id'  => ['nullable', 'exists:operadores_viaje,id'],
            'tipo_transporte_id' => ['nullable', 'exists:tipo_transporte,id'],
            'estado_reserva_id'  => ['nullable', 'exists:estado_reserva,id'],
            'titulo'             => ['nullable', 'string', 'max:255'],
            'codigo_reserva'     => ['nullable', 'string', 'max:255'],
            'fecha_inicio'       => ['nullable', 'date'],
            'fecha_fin'          => ['nullable', 'date'],
            'monto'              => ['nullable', 'numeric', 'min:0'],
            'moneda_id'          => ['nullable', 'exists:monedas,id'],
            'origen'             => ['nullable', 'string', 'max:255'],
            'destino'            => ['nullable', 'string', 'max:255'],
            'numero_servicio'    => ['nullable', 'string', 'max:100'],
            'asiento'            => ['nullable', 'string', 'max:100'],
            'num_noches'         => ['nullable', 'integer', 'min:1'],
            'tipo_habitacion'    => ['nullable', 'string', 'max:100'],
            'direccion'          => ['nullable', 'string'],
            'notas'              => ['nullable', 'string'],
        ]);

        $reserva->update($data);

        return redirect()->route('dashboard.viajes.show', $reserva->viaje_id)
            ->with('success', 'Reserva actualizada correctamente.')
            ->with('activeTab', 'tabReservas');
    }

    public function destroy(Reserva $reserva): RedirectResponse
    {
        abort_unless($reserva->viaje?->hogar_id === $this->hogarId(), 403);

        $viajeId = $reserva->viaje_id;
        $reserva->delete();

        return redirect()->route('dashboard.viajes.show', $viajeId)
            ->with('success', 'Reserva eliminada correctamente.')
            ->with('activeTab', 'tabReservas');
    }
}

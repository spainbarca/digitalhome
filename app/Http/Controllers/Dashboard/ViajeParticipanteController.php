<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use App\Models\ViajeParticipante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViajeParticipanteController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Viaje $viaje): RedirectResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'hogar_miembro_id' => ['required', 'exists:hogar_miembros,id'],
            'rol'              => ['nullable', 'string', 'max:100'],
        ]);

        // Evitar duplicados (por SoftDeletes no hay índice único)
        $yaExiste = ViajeParticipante::where('viaje_id', $viaje->id)
            ->where('hogar_miembro_id', $data['hogar_miembro_id'])
            ->exists();

        if (!$yaExiste) {
            $data['viaje_id'] = $viaje->id;
            ViajeParticipante::create($data);
        }

        return redirect()->route('dashboard.viajes.show', $viaje)
            ->with('success', $yaExiste ? 'El miembro ya es participante.' : 'Participante agregado correctamente.')
            ->with('activeTab', 'tabParticipantes');
    }

    public function destroy(ViajeParticipante $viajeParticipante): RedirectResponse
    {
        abort_unless($viajeParticipante->viaje?->hogar_id === $this->hogarId(), 403);

        $viajeId = $viajeParticipante->viaje_id;
        $viajeParticipante->delete();

        return redirect()->route('dashboard.viajes.show', $viajeId)
            ->with('success', 'Participante eliminado correctamente.')
            ->with('activeTab', 'tabParticipantes');
    }
}

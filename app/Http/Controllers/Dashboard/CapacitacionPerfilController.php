<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Capacitacion;
use App\Models\HogarMiembro;
use Illuminate\Support\Facades\Auth;

class CapacitacionPerfilController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index()
    {
        $hogarId = $this->hogarId();

        $miembros = HogarMiembro::where('hogar_id', $hogarId)
            ->with('user.persona')
            ->withCount(['capacitaciones' => fn($q) => $q->where('activo', true)])
            ->get();

        return view('dashboard.capacitaciones-perfil.index', compact('miembros'));
    }

    public function show(HogarMiembro $miembro)
    {
        abort_unless($miembro->hogar_id === $this->hogarId(), 403);

        $miembro->load('user.persona');

        $capacitaciones = Capacitacion::where('hogar_miembro_id', $miembro->id)
            ->with(['tipoCapacitacion', 'institucionEducativa', 'empleo.empleador.empresa'])
            ->orderByDesc('fecha_fin')
            ->orderByDesc('fecha_inicio')
            ->get();

        $totalHoras = $capacitaciones->sum('horas_academicas');

        return view('dashboard.capacitaciones-perfil.show', compact('miembro', 'capacitaciones', 'totalHoras'));
    }
}

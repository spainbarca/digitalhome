<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCapacitacionRequest;
use App\Http\Requests\UpdateCapacitacionRequest;
use App\Models\Capacitacion;
use App\Models\Empleo;
use App\Models\HogarMiembro;
use App\Models\InstitucionEducativa;
use App\Models\TipoCapacitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CapacitacionController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request)
    {
        $hogarId = $this->hogarId();

        $q = Capacitacion::where('hogar_id', $hogarId)
            ->with(['hogarMiembro.user.persona', 'tipoCapacitacion', 'institucionEducativa', 'empleo.empleador']);

        if ($request->filled('miembro_id')) {
            $q->where('hogar_miembro_id', $request->miembro_id);
        }
        if ($request->filled('tipo_id')) {
            $q->where('tipo_capacitacion_id', $request->tipo_id);
        }
        if ($request->filled('vinculo')) {
            if ($request->vinculo === 'libre') {
                $q->whereNull('empleo_id');
            } elseif ($request->vinculo === 'empleo') {
                $q->whereNotNull('empleo_id');
            }
        }
        if ($request->filled('q')) {
            $q->where('nombre', 'like', '%' . $request->q . '%');
        }

        $capacitaciones = $q->orderByDesc('fecha_fin')->paginate(20)->withQueryString();
        $miembros       = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $tipos          = TipoCapacitacion::orderBy('nombre')->get();

        return view('dashboard.capacitaciones.index', compact('capacitaciones', 'miembros', 'tipos'));
    }

    public function create()
    {
        $hogarId       = $this->hogarId();
        $miembros      = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $empleos       = Empleo::where('hogar_id', $hogarId)
            ->with('empleador.empresa')
            ->orderByDesc('fecha_inicio')->get();
        $tipos         = TipoCapacitacion::orderBy('nombre')->get();
        $instituciones = InstitucionEducativa::where('hogar_id', $hogarId)
            ->orderBy('nombre_referencial')->get();

        $empleosData = $empleos->map(function ($e) {
            $empresa = $e->empleador?->empresa?->razon_social ?? $e->empleador?->nombre;
            return [
                'id'         => $e->id,
                'miembro_id' => $e->hogar_miembro_id,
                'text'       => $e->cargo . ($empresa ? ' — ' . $empresa : ''),
            ];
        })->values();

        return view('dashboard.capacitaciones.create', compact('miembros', 'empleos', 'empleosData', 'tipos', 'instituciones'));
    }

    public function store(StoreCapacitacionRequest $request)
    {
        $data             = $request->validated();
        $data['hogar_id'] = $this->hogarId();

        if ($request->hasFile('archivo')) {
            $data['file_path'] = $request->file('archivo')->store('capacitaciones', 'public');
        }
        unset($data['archivo']);

        $cap = Capacitacion::create($data);

        return redirect()->route('dashboard.capacitaciones.show', $cap)
            ->with('success', 'Capacitación registrada correctamente.');
    }

    public function show(Capacitacion $capacitacion)
    {
        abort_unless($capacitacion->hogar_id === $this->hogarId(), 403);

        $capacitacion->load([
            'hogarMiembro.user.persona',
            'tipoCapacitacion',
            'institucionEducativa',
            'empleo.empleador',
        ]);

        return view('dashboard.capacitaciones.show', compact('capacitacion'));
    }

    public function edit(Capacitacion $capacitacion)
    {
        abort_unless($capacitacion->hogar_id === $this->hogarId(), 403);

        $hogarId       = $this->hogarId();
        $miembros      = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $empleos       = Empleo::where('hogar_id', $hogarId)
            ->with('empleador.empresa')
            ->orderByDesc('fecha_inicio')->get();
        $tipos         = TipoCapacitacion::orderBy('nombre')->get();
        $instituciones = InstitucionEducativa::where('hogar_id', $hogarId)
            ->orderBy('nombre_referencial')->get();

        $empleosData = $empleos->map(function ($e) {
            $empresa = $e->empleador?->empresa?->razon_social ?? $e->empleador?->nombre;
            return [
                'id'         => $e->id,
                'miembro_id' => $e->hogar_miembro_id,
                'text'       => $e->cargo . ($empresa ? ' — ' . $empresa : ''),
            ];
        })->values();

        return view('dashboard.capacitaciones.edit', compact('capacitacion', 'miembros', 'empleos', 'empleosData', 'tipos', 'instituciones'));
    }

    public function update(UpdateCapacitacionRequest $request, Capacitacion $capacitacion)
    {
        abort_unless($capacitacion->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();

        if ($request->hasFile('archivo')) {
            if ($capacitacion->file_path) {
                Storage::disk('public')->delete($capacitacion->file_path);
            }
            $data['file_path'] = $request->file('archivo')->store('capacitaciones', 'public');
        }
        unset($data['archivo']);

        $capacitacion->update($data);

        if ($request->input('_redirect') === 'show') {
            return redirect()->route('dashboard.capacitaciones.show', $capacitacion)
                ->with('success', 'Capacitación actualizada correctamente.');
        }

        return back()->with('success', 'Capacitación actualizada correctamente.');
    }

    public function destroy(Capacitacion $capacitacion)
    {
        abort_unless($capacitacion->hogar_id === $this->hogarId(), 403);

        if ($capacitacion->file_path) {
            Storage::disk('public')->delete($capacitacion->file_path);
        }
        $capacitacion->delete();

        if (request()->input('_redirect') === 'index') {
            return redirect()->route('dashboard.capacitaciones.index')
                ->with('success', 'Capacitación eliminada correctamente.');
        }

        return back()->with('success', 'Capacitación eliminada correctamente.');
    }

    // ── Sub-recurso: creación desde modal de Empleo ───────────────────────────
    public function storeFromEmpleo(Request $request, Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'tipo_capacitacion_id'     => ['nullable', 'exists:tipo_capacitacion,id'],
            'institucion_educativa_id' => ['nullable', 'exists:instituciones_educativas,id'],
            'nombre'                   => ['required', 'string', 'max:255'],
            'descripcion'              => ['nullable', 'string'],
            'institucion_nombre'       => ['nullable', 'string', 'max:255'],
            'fecha_inicio'             => ['nullable', 'date'],
            'fecha_fin'                => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'fecha_vencimiento'        => ['nullable', 'date'],
            'codigo_certificado'       => ['nullable', 'string', 'max:255'],
            'url_verificacion'         => ['nullable', 'url', 'max:255'],
            'horas_academicas'         => ['nullable', 'integer', 'min:0'],
            'notas'                    => ['nullable', 'string'],
            'archivo'                  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'activo'                   => ['nullable', 'boolean'],
        ]);

        $data['hogar_id']         = $empleo->hogar_id;
        $data['hogar_miembro_id'] = $empleo->hogar_miembro_id;

        if ($request->hasFile('archivo')) {
            $data['file_path'] = $request->file('archivo')->store('capacitaciones', 'public');
        }
        unset($data['archivo']);

        $empleo->capacitaciones()->create($data);

        return back()->with('success', 'Capacitación agregada correctamente.');
    }
}

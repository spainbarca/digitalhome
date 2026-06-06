<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatriculaRequest;
use App\Http\Requests\UpdateMatriculaRequest;
use App\Models\EstadoMatricula;
use App\Models\HogarMiembro;
use App\Models\InstitucionEducativa;
use App\Models\Matricula;
use App\Models\Moneda;
use App\Models\NivelEducativo;
use App\Models\TipoInstitucionEducativa;
use App\Models\TurnoEducativo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MatriculaController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizar(Matricula $matricula): void
    {
        abort_unless($matricula->hogar_id === $this->hogarId(), 403);
    }

    private function miembrosHogar(): \Illuminate\Support\Collection
    {
        return HogarMiembro::where('hogar_id', $this->hogarId())
            ->with('user.persona')
            ->get()
            ->sortBy(fn ($m) => trim(
                ($m->user?->persona?->nombres ?? '') . ' ' .
                ($m->user?->persona?->apellido_paterno ?? '')
            ));
    }

    public static function nombreMiembro(HogarMiembro $m): string
    {
        $nombre = trim(implode(' ', array_filter([
            $m->user?->persona?->nombres,
            $m->user?->persona?->apellido_paterno,
            $m->user?->persona?->apellido_materno,
        ])));
        return $nombre ?: ($m->user?->name ?? 'Sin nombre');
    }

    public function index(Request $request): View
    {
        $hogarId   = $this->hogarId();
        $miembroId = $request->get('miembro_id', '');
        $tipoId    = $request->get('tipo_id', '');
        $estadoId  = $request->get('estado_id', '');

        $matriculas = Matricula::with([
                'hogarMiembro.user.persona',
                'institucionEducativa.tipoInstitucionEducativa',
                'nivelEducativo',
                'estadoMatricula',
                'moneda',
            ])
            ->where('hogar_id', $hogarId)
            ->when($miembroId, fn ($q) => $q->where('hogar_miembro_id', $miembroId))
            ->when($tipoId, fn ($q) => $q->whereHas('institucionEducativa', fn ($iq) =>
                $iq->where('tipo_institucion_educativa_id', $tipoId)
            ))
            ->when($estadoId, fn ($q) => $q->where('estado_matricula_id', $estadoId))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $miembros = $this->miembrosHogar();
        $tipos    = TipoInstitucionEducativa::where('activo', true)->orderBy('nombre')->get();
        $estados  = EstadoMatricula::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.matriculas.index', compact(
            'matriculas', 'miembros', 'tipos', 'estados',
            'miembroId', 'tipoId', 'estadoId'
        ));
    }

    public function create(): View
    {
        $preselMiembro = request('hogar_miembro_id', '');
        $preselInst    = request('institucion_educativa_id', '');

        $miembros      = $this->miembrosHogar();
        $instituciones = InstitucionEducativa::where('hogar_id', $this->hogarId())
                            ->where('activo', true)
                            ->with('tipoInstitucionEducativa')
                            ->orderBy('nombre_referencial')
                            ->get();
        $niveles  = NivelEducativo::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $turnos   = TurnoEducativo::where('activo', true)->orderBy('nombre')->get();
        $estados  = EstadoMatricula::where('activo', true)->orderBy('nombre')->get();
        $monedas  = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.matriculas.create', compact(
            'miembros', 'instituciones', 'niveles', 'turnos', 'estados', 'monedas',
            'preselMiembro', 'preselInst'
        ));
    }

    public function store(StoreMatriculaRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['hogar_id'] = $this->hogarId();

        $matricula = Matricula::create($data);

        return redirect()->route('dashboard.matriculas.show', $matricula)
            ->with('success', 'Matrícula registrada correctamente.');
    }

    public function show(Matricula $matricula): View
    {
        $this->autorizar($matricula);

        $matricula->load([
            'hogarMiembro.user.persona',
            'institucionEducativa.tipoInstitucionEducativa',
            'institucionEducativa.empresa',
            'nivelEducativo',
            'turnoEducativo',
            'estadoMatricula',
            'moneda',
            'pagosEducativos.moneda',
            'documentosEducativos.tipoDocumentoEducativo',
        ]);

        return view('dashboard.matriculas.show', compact('matricula'));
    }

    public function edit(Matricula $matricula): View
    {
        $this->autorizar($matricula);

        $miembros      = $this->miembrosHogar();
        $instituciones = InstitucionEducativa::where('hogar_id', $this->hogarId())
                            ->where('activo', true)
                            ->with('tipoInstitucionEducativa')
                            ->orderBy('nombre_referencial')
                            ->get();
        $niveles  = NivelEducativo::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $turnos   = TurnoEducativo::where('activo', true)->orderBy('nombre')->get();
        $estados  = EstadoMatricula::where('activo', true)->orderBy('nombre')->get();
        $monedas  = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.matriculas.edit', compact(
            'matricula', 'miembros', 'instituciones', 'niveles', 'turnos', 'estados', 'monedas'
        ));
    }

    public function update(UpdateMatriculaRequest $request, Matricula $matricula): RedirectResponse
    {
        $this->autorizar($matricula);
        $matricula->update($request->validated());

        return redirect()->route('dashboard.matriculas.show', $matricula)
            ->with('success', 'Matrícula actualizada correctamente.');
    }

    public function destroy(Matricula $matricula): RedirectResponse
    {
        $this->autorizar($matricula);
        $matricula->delete();

        return redirect()->route('dashboard.matriculas.index')
            ->with('success', 'Matrícula eliminada correctamente.');
    }

    public function porMiembro(HogarMiembro $miembro): JsonResponse
    {
        abort_unless($miembro->hogar_id === $this->hogarId(), 403);

        $matriculas = Matricula::where('hogar_id', $this->hogarId())
            ->where('hogar_miembro_id', $miembro->id)
            ->with(['institucionEducativa', 'nivelEducativo'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($m) => [
                'id'    => $m->id,
                'label' => ($m->institucionEducativa?->nombre_referencial ?? '(Sin institución)')
                    . ($m->año_lectivo ? ' — ' . $m->año_lectivo : '')
                    . ($m->grado_ciclo ? ' · ' . $m->grado_ciclo : ''),
            ]);

        return response()->json($matriculas);
    }
}

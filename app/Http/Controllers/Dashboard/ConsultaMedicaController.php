<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultaMedicaRequest;
use App\Http\Requests\UpdateConsultaMedicaRequest;
use App\Models\CentroMedico;
use App\Models\ConsultaMedica;
use App\Models\EspecialidadMedica;
use App\Models\HogarMiembro;
use App\Models\Medico;
use App\Models\Moneda;
use App\Models\TipoDocumentoMedico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConsultaMedicaController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizarConsulta(ConsultaMedica $consulta): void
    {
        abort_unless($consulta->hogar_id === $this->hogarId(), 403);
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

    public static function nombreMiembro(\App\Models\HogarMiembro $m): string
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
        $hogarId        = $this->hogarId();
        $search         = $request->get('search', '');
        $miembroId      = $request->get('hogar_miembro_id', '');
        $especialidadId = $request->get('especialidad_medica_id', '');
        $fechaDesde     = $request->get('fecha_desde', '');
        $fechaHasta     = $request->get('fecha_hasta', '');

        $consultas = ConsultaMedica::with(['hogarMiembro.user.persona', 'medico', 'especialidadMedica'])
            ->where('hogar_id', $hogarId)
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('motivo', 'like', "%{$search}%")
                  ->orWhere('diagnostico', 'like', "%{$search}%");
            }))
            ->when($miembroId, fn ($q) => $q->where('hogar_miembro_id', $miembroId))
            ->when($especialidadId, fn ($q) => $q->where('especialidad_medica_id', $especialidadId))
            ->when($fechaDesde, fn ($q) => $q->where('fecha', '>=', $fechaDesde))
            ->when($fechaHasta, fn ($q) => $q->where('fecha', '<=', $fechaHasta))
            ->orderBy('fecha', 'desc')
            ->paginate(15)
            ->withQueryString();

        $miembros       = $this->miembrosHogar();
        $especialidades = EspecialidadMedica::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.consultas-medicas.index', compact(
            'consultas', 'search', 'miembroId', 'especialidadId', 'fechaDesde', 'fechaHasta',
            'miembros', 'especialidades'
        ));
    }

    public function create(): View
    {
        $miembros       = $this->miembrosHogar();
        $medicos        = Medico::where('hogar_id', $this->hogarId())->where('activo', true)
                            ->with('especialidadMedica')->orderBy('apellidos')->get();
        $centrosMedicos = CentroMedico::where('hogar_id', $this->hogarId())->where('activo', true)
                            ->with('empresa')->orderBy('nombre_referencial')->get();
        $especialidades = EspecialidadMedica::where('activo', true)->orderBy('nombre')->get();
        $monedas        = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.consultas-medicas.create', compact(
            'miembros', 'medicos', 'centrosMedicos', 'especialidades', 'monedas'
        ));
    }

    public function store(StoreConsultaMedicaRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['hogar_id'] = $this->hogarId();
        $data['hora']     = $request->filled('hora') ? $request->hora : null;
        $data['costo']    = $request->filled('costo') ? $request->costo : null;

        $consulta = ConsultaMedica::create($data);

        return redirect()->route('dashboard.consultas-medicas.show', $consulta)
            ->with('success', 'Consulta médica registrada correctamente.');
    }

    public function show(ConsultaMedica $consulta): View
    {
        $this->autorizarConsulta($consulta);
        $consulta->load([
            'hogarMiembro.user.persona',
            'medico',
            'centroMedico.empresa',
            'especialidadMedica',
            'moneda',
            'documentosMedicos.tipoDocumentoMedico',
            'documentosMedicos.hogarMiembro.user.persona',
        ]);
        $tiposDocumento = TipoDocumentoMedico::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.consultas-medicas.show', compact('consulta', 'tiposDocumento'));
    }

    public function edit(ConsultaMedica $consulta): View
    {
        $this->autorizarConsulta($consulta);

        $miembros       = $this->miembrosHogar();
        $medicos        = Medico::where('hogar_id', $this->hogarId())->where('activo', true)
                            ->with('especialidadMedica')->orderBy('apellidos')->get();
        $centrosMedicos = CentroMedico::where('hogar_id', $this->hogarId())->where('activo', true)
                            ->with('empresa')->orderBy('nombre_referencial')->get();
        $especialidades = EspecialidadMedica::where('activo', true)->orderBy('nombre')->get();
        $monedas        = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.consultas-medicas.edit', compact(
            'consulta', 'miembros', 'medicos', 'centrosMedicos', 'especialidades', 'monedas'
        ));
    }

    public function update(UpdateConsultaMedicaRequest $request, ConsultaMedica $consulta): RedirectResponse
    {
        $this->autorizarConsulta($consulta);

        $data          = $request->validated();
        $data['hora']  = $request->filled('hora') ? $request->hora : null;
        $data['costo'] = $request->filled('costo') ? $request->costo : null;

        $consulta->update($data);

        return redirect()->route('dashboard.consultas-medicas.show', $consulta)
            ->with('success', 'Consulta médica actualizada correctamente.');
    }

    public function destroy(ConsultaMedica $consulta): RedirectResponse
    {
        $this->autorizarConsulta($consulta);
        $consulta->delete();

        return redirect()->route('dashboard.consultas-medicas.index')
            ->with('success', 'Consulta médica eliminada correctamente.');
    }

    public function porMiembro(HogarMiembro $miembro): JsonResponse
    {
        abort_unless($miembro->hogar_id === $this->hogarId(), 403);

        $consultas = ConsultaMedica::where('hogar_id', $this->hogarId())
            ->where('hogar_miembro_id', $miembro->id)
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(fn ($c) => [
                'id'    => $c->id,
                'label' => $c->fecha?->format('d/m/Y')
                    . ($c->hora ? ' ' . substr($c->hora, 0, 5) : '')
                    . ($c->motivo ? ' — ' . Str::limit($c->motivo, 60) : ''),
            ]);

        return response()->json($consultas);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpleoRequest;
use App\Http\Requests\UpdateEmpleoRequest;
use App\Models\Empleo;
use App\Models\Empleador;
use App\Models\EstadoEmpleo;
use App\Models\HogarMiembro;
use App\Models\ModalidadLaboral;
use App\Models\Moneda;
use App\Models\InstitucionEducativa;
use App\Models\TipoBeneficio;
use App\Models\TipoCapacitacion;
use App\Models\TipoDocumentoLaboral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request)
    {
        $hogarId = $this->hogarId();

        $q = Empleo::where('hogar_id', $hogarId)
            ->with(['hogarMiembro.user.persona', 'empleador.empresa', 'estadoEmpleo', 'modalidadLaboral', 'moneda']);

        if ($request->filled('miembro_id'))   $q->where('hogar_miembro_id', $request->miembro_id);
        if ($request->filled('estado_id'))    $q->where('estado_empleo_id', $request->estado_id);
        if ($request->filled('empleador_id')) $q->where('empleador_id', $request->empleador_id);

        $empleos    = $q->orderByRaw('es_actual DESC')->orderByDesc('fecha_inicio')->get();
        $miembros   = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $estados    = EstadoEmpleo::where('activo', true)->orderBy('nombre')->get();
        $empleadores = Empleador::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.empleos.index', compact('empleos', 'miembros', 'estados', 'empleadores'));
    }

    public function create()
    {
        $hogarId     = $this->hogarId();
        $miembros    = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $empleadores = Empleador::where('activo', true)->orderBy('nombre')->get();
        $modalidades = ModalidadLaboral::where('activo', true)->orderBy('nombre')->get();
        $estados     = EstadoEmpleo::where('activo', true)->orderBy('nombre')->get();
        $monedas     = Moneda::orderBy('nombre')->get();
        $monedaLocal = Moneda::where('moneda_local', true)->first();

        return view('dashboard.empleos.create', compact(
            'miembros', 'empleadores', 'modalidades', 'estados', 'monedas', 'monedaLocal'
        ));
    }

    public function store(StoreEmpleoRequest $request)
    {
        $data             = $request->validated();
        $data['hogar_id'] = $this->hogarId();
        $empleo           = Empleo::create($data);

        return redirect()->route('dashboard.empleos.show', $empleo)
            ->with('success', 'Empleo registrado correctamente.');
    }

    public function show(Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $empleo->load([
            'hogarMiembro.user.persona',
            'empleador.empresa',
            'estadoEmpleo',
            'modalidadLaboral',
            'moneda',
            'documentosLaborales.tipoDocumentoLaboral',
            'empleoBeneficios.tipoBeneficio',
            'empleoReferencias',
            'capacitaciones.tipoCapacitacion',
            'capacitaciones.institucionEducativa',
        ]);

        $tiposDocumentoLaboral  = TipoDocumentoLaboral::where('activo', true)->orderBy('nombre')->get();
        $tiposBeneficio         = TipoBeneficio::where('activo', true)->orderBy('nombre')->get();
        $tiposCapacitacion      = TipoCapacitacion::where('activo', true)->orderBy('nombre')->get();
        $institucionesEducativas = InstitucionEducativa::where('hogar_id', $empleo->hogar_id)
            ->where('activo', true)
            ->orderBy('nombre_referencial')
            ->get();

        return view('dashboard.empleos.show', compact(
            'empleo', 'tiposDocumentoLaboral', 'tiposBeneficio', 'tiposCapacitacion', 'institucionesEducativas'
        ));
    }

    public function edit(Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $hogarId     = $this->hogarId();
        $miembros    = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $empleadores = Empleador::orderBy('nombre')->get();
        $modalidades = ModalidadLaboral::where('activo', true)->orderBy('nombre')->get();
        $estados     = EstadoEmpleo::where('activo', true)->orderBy('nombre')->get();
        $monedas     = Moneda::orderBy('nombre')->get();

        return view('dashboard.empleos.edit', compact(
            'empleo', 'miembros', 'empleadores', 'modalidades', 'estados', 'monedas'
        ));
    }

    public function update(UpdateEmpleoRequest $request, Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $empleo->update($request->validated());

        return redirect()->route('dashboard.empleos.show', $empleo)
            ->with('success', 'Empleo actualizado correctamente.');
    }

    public function destroy(Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $empleo->delete();

        return redirect()->route('dashboard.empleos.index')
            ->with('success', 'Empleo eliminado correctamente.');
    }
}

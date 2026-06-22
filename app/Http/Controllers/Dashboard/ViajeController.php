<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreViajeRequest;
use App\Http\Requests\UpdateViajeRequest;
use App\Models\CategoriaGastoViaje;
use App\Models\Ciudad;
use App\Models\Compra;
use App\Models\EstadoReserva;
use App\Models\HogarMiembro;
use App\Models\Moneda;
use App\Models\OperadorViaje;
use App\Models\Pais;
use App\Models\TipoDocumentoViaje;
use App\Models\TipoReserva;
use App\Models\TipoTransporte;
use App\Models\EstadoViaje;
use App\Models\TipoViaje;
use App\Models\UbigeoDepartamento;
use App\Models\UbigeoDistrito;
use App\Models\UbigeoProvincia;
use App\Models\DocumentoLegal;
use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ViajeController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function scopedQuery()
    {
        return Viaje::where('hogar_id', $this->hogarId());
    }

    public function index(): View
    {
        $hoy   = now()->startOfDay();
        $aviso = now()->addDays(90)->startOfDay();

        $todos = $this->scopedQuery()
            ->with(['tipoViaje', 'moneda', 'estadoViaje'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $proximos = $todos->filter(fn ($v) =>
            $v->fecha_fin === null || $v->fecha_fin->gte($hoy)
        );

        $pasados = $todos->filter(fn ($v) =>
            $v->fecha_fin !== null && $v->fecha_fin->lt($hoy)
        );

        // Alerta de documentos de viajero
        $docViajerosBase = DocumentoLegal::where('hogar_id', $this->hogarId())
            ->where('activo', true)
            ->whereHas('tipoDocumentoLegal', fn($q) => $q->where('relevante_viaje', true))
            ->whereNotNull('fecha_vencimiento')
            ->get(['fecha_vencimiento']);

        $docVencidos  = $docViajerosBase->filter(fn($d) => $d->fecha_vencimiento->lt($hoy))->count();
        $docPorVencer = $docViajerosBase->filter(fn($d) => $d->fecha_vencimiento->gte($hoy) && $d->fecha_vencimiento->lte($aviso))->count();

        return view('dashboard.viajes.index', compact(
            'proximos', 'pasados', 'docVencidos', 'docPorVencer'
        ));
    }

    public function create(): View
    {
        return view('dashboard.viajes.create', $this->formData());
    }

    public function store(StoreViajeRequest $request): RedirectResponse
    {
        $data            = $request->validated();
        $data['hogar_id'] = $this->hogarId();

        if ($request->hasFile('portada')) {
            $data['portada_path'] = $request->file('portada')->store('viajes/portadas', 'public');
        }
        unset($data['portada']);

        $viaje = Viaje::create($data);

        return redirect()->route('dashboard.viajes.show', $viaje)
            ->with('success', 'Viaje registrado correctamente.');
    }

    public function show(Viaje $viaje): View
    {
        $hogarId = $this->hogarId();
        abort_unless($viaje->hogar_id === $hogarId, 403);

        $viaje->load(['tipoViaje', 'moneda', 'estadoViaje']);

        $destinos = $viaje->destinos()->with(['distrito', 'pais', 'ciudad'])->orderBy('orden')->get();

        $reservas = $viaje->reservas()
            ->with(['operadorViaje.empresa', 'tipoReserva', 'estadoReserva', 'moneda', 'tipoTransporte'])
            ->orderBy('fecha_inicio')
            ->get();

        $comprasViaje = Compra::where('viaje_id', $viaje->id)
            ->with(['moneda', 'comercio.empresa', 'categoriaCompra'])
            ->orderBy('fecha_compra', 'desc')
            ->get();

        $gastosViaje = $viaje->gastos()
            ->with(['categoria', 'moneda', 'hogarMiembro.user.persona'])
            ->orderBy('fecha', 'desc')
            ->get();

        // Agrupación por moneda (sin convertir)
        $resumenGastos = [];
        foreach ($comprasViaje as $c) {
            $mid = $c->moneda_id ?? '_sin';
            if (!isset($resumenGastos[$mid])) {
                $resumenGastos[$mid] = ['moneda' => $c->moneda, 'compras' => 0, 'gastos' => 0];
            }
            $resumenGastos[$mid]['compras'] += (float) $c->total;
        }
        foreach ($gastosViaje as $g) {
            $mid = $g->moneda_id ?? '_sin';
            if (!isset($resumenGastos[$mid])) {
                $resumenGastos[$mid] = ['moneda' => $g->moneda, 'compras' => 0, 'gastos' => 0];
            }
            $resumenGastos[$mid]['gastos'] += (float) $g->monto;
        }

        $participantes = $viaje->participantes()
            ->with('hogarMiembro.user.persona')
            ->get();

        $documentos = $viaje->documentos()
            ->with(['tipoDocumentoViaje', 'reserva'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Listas para modales
        $operadores = OperadorViaje::with(['empresa', 'tipoOperadorViaje'])
            ->get()
            ->sortBy('nombre_comercial_resuelto')
            ->values();

        $tiposReserva     = TipoReserva::where('activo', true)->orderBy('nombre')->get();
        $tiposTransporte  = TipoTransporte::where('activo', true)->orderBy('nombre')->get();
        $estadosReserva   = EstadoReserva::where('activo', true)->orderBy('nombre')->get();
        $monedas          = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();
        $miembros         = HogarMiembro::with('user.persona')
                                ->where('hogar_id', $hogarId)
                                ->where('estado', 'activo')
                                ->get();
        $categoriasGasto  = CategoriaGastoViaje::where('activo', true)->orderBy('nombre')->get();
        $tiposDocViaje    = TipoDocumentoViaje::where('activo', true)->orderBy('nombre')->get();
        $reservasSelect   = $reservas;

        // Ubigeo + paises/ciudades para modal destino
        $departamentos = UbigeoDepartamento::orderBy('departamento')->get(['inei', 'departamento']);
        $paises        = Pais::orderBy('nombre')->get(['iso2', 'nombre']);

        // Arrays pre-construidos para los <script> (sin closures en la vista)
        $provinciasArr = UbigeoProvincia::orderBy('provincia')
            ->get(['inei', 'provincia', 'departamento_inei'])
            ->map(function ($p) {
                return ['inei' => $p->inei, 'nombre' => $p->provincia, 'departamento_inei' => $p->departamento_inei];
            })->values()->all();

        $distritosArr = UbigeoDistrito::orderBy('distrito')
            ->get(['inei', 'distrito', 'provincia_inei'])
            ->map(function ($d) {
                return ['inei' => $d->inei, 'nombre' => $d->distrito, 'provincia_inei' => $d->provincia_inei];
            })->values()->all();

        $ciudadesArr = Ciudad::orderBy('nombre')
            ->get(['id', 'nombre', 'pais_iso2'])
            ->map(function ($c) {
                return ['id' => $c->id, 'nombre' => $c->nombre, 'pais_iso2' => $c->pais_iso2];
            })->values()->all();

        $checklist = $viaje->checklist()->with('hogarMiembro.user.persona')->get();

        return view('dashboard.viajes.show', compact(
            'viaje', 'destinos', 'reservas', 'comprasViaje', 'gastosViaje', 'resumenGastos',
            'participantes', 'documentos', 'checklist',
            'operadores', 'tiposReserva', 'tiposTransporte', 'estadosReserva',
            'monedas', 'miembros', 'categoriasGasto', 'tiposDocViaje', 'reservasSelect',
            'departamentos', 'paises', 'provinciasArr', 'distritosArr', 'ciudadesArr',
        ));
    }

    public function edit(Viaje $viaje): View
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        return view('dashboard.viajes.edit', array_merge(
            $this->formData(),
            ['viaje' => $viaje]
        ));
    }

    public function update(UpdateViajeRequest $request, Viaje $viaje): RedirectResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();
        unset($data['portada']);

        if ($request->hasFile('portada')) {
            if ($viaje->portada_path) {
                Storage::disk('public')->delete($viaje->portada_path);
            }
            $data['portada_path'] = $request->file('portada')->store('viajes/portadas', 'public');
        }

        $viaje->update($data);

        return redirect()->route('dashboard.viajes.show', $viaje)
            ->with('success', 'Viaje actualizado correctamente.');
    }

    public function destroy(Viaje $viaje): RedirectResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $viaje->delete();

        return redirect()->route('dashboard.viajes.index')
            ->with('success', 'Viaje eliminado correctamente.');
    }

    private function formData(): array
    {
        $tipos         = TipoViaje::where('activo', true)->orderBy('nombre')->get();
        $monedas       = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();
        $estadosViaje  = EstadoViaje::where('activo', true)->orderBy('nombre')->get();

        return compact('tipos', 'monedas', 'estadosViaje');
    }
}

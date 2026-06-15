<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrestatarioRequest;
use App\Http\Requests\UpdatePrestatarioRequest;
use App\Models\ConceptoPago;
use App\Models\HogarMiembro;
use App\Models\MetodoPago;
use App\Models\Moneda;
use App\Models\Prestatario;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrestatarioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function miembroId(): ?string
    {
        return HogarMiembro::where('hogar_id', $this->hogarId())
            ->where('user_id', Auth::id())
            ->value('id');
    }

    public function index(Request $request)
    {
        $hogarId = $this->hogarId();

        $query = Prestatario::with(['hogarMiembro.user.persona', 'moneda', 'movimientosPrestamo'])
            ->where('hogar_id', $hogarId);

        if ($request->filled('q')) {
            $query->where('nombre', 'like', '%' . $request->q . '%');
        }

        $todos = $query->orderBy('nombre')->get();

        // Deuda total del hogar (suma de saldos positivos, independiente de filtros)
        $deudaTotal = $todos->sum(fn ($p) => max(0.0, $p->saldo));

        $filtro = $request->get('filtro', 'todos');
        $filtrados = $todos->filter(fn ($p) => match ($filtro) {
            'con-deuda' => $p->saldo > 0,
            'saldados'  => $p->saldo <= 0,
            default     => true,
        });

        $perPage  = 15;
        $page     = max(1, (int) $request->get('page', 1));
        $items    = $filtrados->forPage($page, $perPage)->values();

        $prestatarios = new LengthAwarePaginator($items, $filtrados->count(), $perPage, $page, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);

        return view('dashboard.prestatarios.index', compact('prestatarios', 'deudaTotal', 'filtro'));
    }

    public function create()
    {
        $miembros = HogarMiembro::with('user.persona')
            ->where('hogar_id', $this->hogarId())
            ->where('estado', 'activo')
            ->orderBy('apodo')
            ->get();
        $monedas = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.prestatarios.create', compact('miembros', 'monedas'));
    }

    public function store(StorePrestatarioRequest $request)
    {
        $data                    = $request->validated();
        $data['hogar_id']        = $this->hogarId();
        $data['miembro_id']      = $this->miembroId();
        $data['es_miembro_hogar'] = $request->boolean('es_miembro_hogar');

        if (!$data['es_miembro_hogar']) {
            $data['hogar_miembro_id'] = null;
        }

        if ($request->hasFile('foto')) {
            $file             = $request->file('foto');
            $uuid             = Str::uuid();
            $ext              = $file->getClientOriginalExtension();
            $data['foto_path'] = $file->storeAs("prestatarios/{$uuid}", "foto.{$ext}", 'public');
        }
        unset($data['foto']);

        $prestatario = Prestatario::create($data);

        return redirect()->route('dashboard.prestamos.prestatarios.show', $prestatario)
            ->with('success', 'Prestatario registrado correctamente.');
    }

    public function show(Prestatario $prestatario)
    {
        abort_unless($prestatario->hogar_id === $this->hogarId(), 403);

        $prestatario->load([
            'hogarMiembro.user.persona',
            'moneda',
            'movimientosPrestamo.conceptoPago.categoriaConcepto',
            'movimientosPrestamo.metodoPago',
        ]);

        // Saldo corriente acumulado (cronológico ASC → reverse para timeline DESC)
        $saldoCorr = 0.0;
        $conSaldo  = $prestatario->movimientosPrestamo
            ->sortBy('fecha')
            ->map(function ($m) use (&$saldoCorr) {
                $saldoCorr += $m->signo_monto;
                $m->saldo_corriente = $saldoCorr;
                return $m;
            })
            ->sortByDesc('fecha')
            ->values();

        $totalPrestado   = $prestatario->movimientosPrestamo->where('tipo', 'cargo')->sum('monto');
        $totalPagado     = $prestatario->movimientosPrestamo->where('tipo', 'abono')->sum('monto');
        $totalDescontado = $prestatario->movimientosPrestamo->where('tipo', 'descuento')->sum('monto');

        $conceptosPago = ConceptoPago::where('hogar_id', $this->hogarId())
            ->where('activo', true)
            ->with('categoriaConcepto')
            ->orderBy('nombre')
            ->get();

        $metodosPago = MetodoPago::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.prestatarios.show', compact(
            'prestatario', 'conSaldo',
            'totalPrestado', 'totalPagado', 'totalDescontado',
            'conceptosPago', 'metodosPago'
        ));
    }

    public function edit(Prestatario $prestatario)
    {
        abort_unless($prestatario->hogar_id === $this->hogarId(), 403);
        $miembros = HogarMiembro::with('user.persona')
            ->where('hogar_id', $this->hogarId())
            ->where('estado', 'activo')
            ->orderBy('apodo')
            ->get();
        $monedas = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.prestatarios.edit', compact('prestatario', 'miembros', 'monedas'));
    }

    public function update(UpdatePrestatarioRequest $request, Prestatario $prestatario)
    {
        abort_unless($prestatario->hogar_id === $this->hogarId(), 403);

        $data                    = $request->validated();
        $data['es_miembro_hogar'] = $request->boolean('es_miembro_hogar');

        if (!$data['es_miembro_hogar']) {
            $data['hogar_miembro_id'] = null;
        }

        if ($request->hasFile('foto')) {
            if ($prestatario->foto_path) {
                Storage::disk('public')->deleteDirectory(dirname($prestatario->foto_path));
            }
            $file             = $request->file('foto');
            $uuid             = Str::uuid();
            $ext              = $file->getClientOriginalExtension();
            $data['foto_path'] = $file->storeAs("prestatarios/{$uuid}", "foto.{$ext}", 'public');
        }
        unset($data['foto']);

        $prestatario->update($data);

        return redirect()->route('dashboard.prestamos.prestatarios.show', $prestatario)
            ->with('success', 'Prestatario actualizado correctamente.');
    }

    public function destroy(Prestatario $prestatario)
    {
        abort_unless($prestatario->hogar_id === $this->hogarId(), 403);

        if ($prestatario->foto_path) {
            Storage::disk('public')->deleteDirectory(dirname($prestatario->foto_path));
        }

        $prestatario->delete();

        return redirect()->route('dashboard.prestamos.prestatarios.index')
            ->with('success', 'Prestatario eliminado correctamente.');
    }
}

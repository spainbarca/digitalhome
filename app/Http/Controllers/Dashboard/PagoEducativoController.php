<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePagoEducativoRequest;
use App\Http\Requests\UpdatePagoEducativoRequest;
use App\Models\HogarMiembro;
use App\Models\Matricula;
use App\Models\Moneda;
use App\Models\PagoEducativo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PagoEducativoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizar(PagoEducativo $pago): void
    {
        abort_unless($pago->hogar_id === $this->hogarId(), 403);
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
        $hogarId      = $this->hogarId();
        $miembroId    = $request->get('miembro_id', '');
        $matriculaId  = $request->get('matricula_id', '');
        $mes          = $request->get('mes_correspondiente', '');
        $estado       = $request->get('estado', '');

        $pagos = PagoEducativo::with([
                'matricula.hogarMiembro.user.persona',
                'matricula.institucionEducativa',
                'moneda',
            ])
            ->where('hogar_id', $hogarId)
            ->when($miembroId, fn ($q) => $q->whereHas('matricula', fn ($mq) =>
                $mq->where('hogar_miembro_id', $miembroId)
            ))
            ->when($matriculaId, fn ($q) => $q->where('matricula_id', $matriculaId))
            ->when($mes, fn ($q) => $q->where('mes_correspondiente', $mes))
            ->when($estado, fn ($q) => $q->where('estado', $estado))
            ->orderByDesc('fecha_vencimiento')
            ->paginate(20)
            ->withQueryString();

        // Resumen del hogar completo (sin filtros de miembro/matrícula/mes/estado)
        $resumen = PagoEducativo::where('hogar_id', $hogarId)->get();
        $totalPagado   = $resumen->where('estado', 'pagado')->sum('monto');
        $totalPendiente = $resumen->where('estado', 'pendiente')->sum('monto');
        $totalVencido  = $resumen->where('estado', 'vencido')->sum('monto');

        $miembros   = $this->miembrosHogar();
        $matriculas = Matricula::where('hogar_id', $hogarId)
            ->with(['hogarMiembro.user.persona', 'institucionEducativa'])
            ->orderByDesc('año_lectivo')
            ->get();

        return view('dashboard.pagos-educativos.index', compact(
            'pagos', 'miembros', 'matriculas',
            'miembroId', 'matriculaId', 'mes', 'estado',
            'totalPagado', 'totalPendiente', 'totalVencido'
        ));
    }

    public function create(Request $request): View
    {
        $hogarId       = $this->hogarId();
        $preselMatricula = $request->get('matricula_id', '');

        $miembros   = $this->miembrosHogar();
        $matriculas = Matricula::where('hogar_id', $hogarId)
            ->with(['hogarMiembro.user.persona', 'institucionEducativa', 'moneda'])
            ->orderByDesc('año_lectivo')
            ->get();
        $monedas = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.pagos-educativos.create', compact(
            'miembros', 'matriculas', 'monedas', 'preselMatricula'
        ));
    }

    public function store(StorePagoEducativoRequest $request): RedirectResponse
    {
        $data             = $request->safe()->except(['comprobante']);
        $data['hogar_id'] = $this->hogarId();

        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');
            $data['comprobante_path']           = $file->store('comprobantes-pagos-educativos', 'public');
            $data['comprobante_nombre_original'] = $file->getClientOriginalName();
            $data['comprobante_mime']            = $file->getMimeType();
            $data['comprobante_size']            = $file->getSize();
        }

        $pago = PagoEducativo::create($data);

        return redirect()->route('dashboard.pagos-educativos.show', $pago)
            ->with('success', 'Pago registrado correctamente.');
    }

    public function show(PagoEducativo $pago): View
    {
        $this->autorizar($pago);
        $pago->load([
            'matricula.hogarMiembro.user.persona',
            'matricula.institucionEducativa',
            'matricula.nivelEducativo',
            'moneda',
        ]);

        return view('dashboard.pagos-educativos.show', compact('pago'));
    }

    public function edit(PagoEducativo $pago): View
    {
        $this->autorizar($pago);
        $hogarId    = $this->hogarId();
        $miembros   = $this->miembrosHogar();
        $matriculas = Matricula::where('hogar_id', $hogarId)
            ->with(['hogarMiembro.user.persona', 'institucionEducativa', 'moneda'])
            ->orderByDesc('año_lectivo')
            ->get();
        $monedas = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();

        return view('dashboard.pagos-educativos.edit', compact(
            'pago', 'miembros', 'matriculas', 'monedas'
        ));
    }

    public function update(UpdatePagoEducativoRequest $request, PagoEducativo $pago): RedirectResponse
    {
        $this->autorizar($pago);

        $data = $request->safe()->except(['comprobante']);

        if ($request->hasFile('comprobante')) {
            if ($pago->comprobante_path) {
                Storage::disk('public')->delete($pago->comprobante_path);
            }
            $file = $request->file('comprobante');
            $data['comprobante_path']           = $file->store('comprobantes-pagos-educativos', 'public');
            $data['comprobante_nombre_original'] = $file->getClientOriginalName();
            $data['comprobante_mime']            = $file->getMimeType();
            $data['comprobante_size']            = $file->getSize();
        }

        $pago->update($data);

        return redirect()->route('dashboard.pagos-educativos.show', $pago)
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(PagoEducativo $pago): RedirectResponse
    {
        $this->autorizar($pago);

        if ($pago->comprobante_path) {
            Storage::disk('public')->delete($pago->comprobante_path);
        }

        $pago->delete();

        return redirect()->route('dashboard.pagos-educativos.index')
            ->with('success', 'Pago eliminado correctamente.');
    }

    public function datosMatricula(Matricula $matricula): JsonResponse
    {
        abort_unless($matricula->hogar_id === $this->hogarId(), 403);

        $matricula->load(['moneda', 'institucionEducativa', 'hogarMiembro.user.persona']);

        $miembroNombre = self::nombreMiembro($matricula->hogarMiembro);

        return response()->json([
            'moneda_id'     => $matricula->moneda_id,
            'costo_mensual' => $matricula->costo_mensual,
            'institucion'   => $matricula->institucionEducativa?->nombre_referencial ?? '',
            'miembro'       => $miembroNombre,
            'moneda_simbolo' => $matricula->moneda?->simbolo ?? '',
        ]);
    }
}

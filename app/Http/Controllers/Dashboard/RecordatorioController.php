<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecordatorioRequest;
use App\Http\Requests\UpdateRecordatorioRequest;
use App\Models\Recordatorio;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecordatorioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizarRecordatorio(Recordatorio $recordatorio): void
    {
        abort_unless($recordatorio->hogar_id === $this->hogarId(), 403);
    }

    public function index(Request $request): View
    {
        $hogarId = $this->hogarId();
        $hoy     = today();

        $vencidos = Recordatorio::where('hogar_id', $hogarId)
            ->where('estado', 'pendiente')
            ->where('fecha_vencimiento', '<', $hoy)
            ->with('user')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        $proximos = Recordatorio::where('hogar_id', $hogarId)
            ->where('estado', 'pendiente')
            ->whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(30)])
            ->with('user')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        $futuros = Recordatorio::where('hogar_id', $hogarId)
            ->where('estado', 'pendiente')
            ->where('fecha_vencimiento', '>', $hoy->copy()->addDays(30))
            ->with('user')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        $descartados = Recordatorio::where('hogar_id', $hogarId)
            ->where('estado', 'descartado')
            ->with('user')
            ->orderBy('fecha_vencimiento', 'desc')
            ->get();

        return view('dashboard.recordatorios.index', compact(
            'vencidos', 'proximos', 'futuros', 'descartados'
        ));
    }

    public function create(): View
    {
        $estados = ['pendiente', 'enviado', 'descartado'];
        return view('dashboard.recordatorios.create', compact('estados'));
    }

    public function store(StoreRecordatorioRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['user_id']  = Auth::id();
        $data['hogar_id'] = $this->hogarId();
        $data['repetir']  = (bool) ($data['repetir'] ?? false);

        // Si fecha_aviso no viene, avisar el mismo día a las 08:00
        if (empty($data['fecha_aviso']) && !empty($data['fecha_vencimiento'])) {
            $data['fecha_aviso'] = Carbon::parse($data['fecha_vencimiento'])->setTime(8, 0);
        } else if (!empty($data['fecha_aviso'])) {
            $data['fecha_aviso'] = Carbon::parse($data['fecha_aviso']);
        }

        // Si repetir=true y fecha ya pasó, avanzar un año
        if (!empty($data['repetir']) && Carbon::parse($data['fecha_vencimiento'])->isPast()) {
            $data['fecha_vencimiento'] = Carbon::parse($data['fecha_vencimiento'])->addYear();
        }

        Recordatorio::create($data);

        return redirect()->route('dashboard.recordatorios.index')
            ->with('success', 'Recordatorio creado correctamente.');
    }

    public function show(Recordatorio $recordatorio): View
    {
        $this->autorizarRecordatorio($recordatorio);
        $recordatorio->load('user');
        return view('dashboard.recordatorios.show', compact('recordatorio'));
    }

    public function edit(Recordatorio $recordatorio): View
    {
        $this->autorizarRecordatorio($recordatorio);
        $estados = ['pendiente', 'enviado', 'descartado'];
        return view('dashboard.recordatorios.edit', compact('recordatorio', 'estados'));
    }

    public function update(UpdateRecordatorioRequest $request, Recordatorio $recordatorio): RedirectResponse
    {
        $this->autorizarRecordatorio($recordatorio);

        $data = $request->validated();
        $data['repetir'] = (bool) ($data['repetir'] ?? false);

        if (empty($data['fecha_aviso']) && !empty($data['fecha_vencimiento'])) {
            $data['fecha_aviso'] = Carbon::parse($data['fecha_vencimiento'])->setTime(8, 0);
        } else if (!empty($data['fecha_aviso'])) {
            $data['fecha_aviso'] = Carbon::parse($data['fecha_aviso']);
        }

        $recordatorio->update($data);

        return redirect()->route('dashboard.recordatorios.show', $recordatorio)
            ->with('success', 'Recordatorio actualizado correctamente.');
    }

    public function destroy(Recordatorio $recordatorio): RedirectResponse
    {
        $this->autorizarRecordatorio($recordatorio);
        $recordatorio->delete();
        return redirect()->route('dashboard.recordatorios.index')
            ->with('success', 'Recordatorio eliminado correctamente.');
    }

    public function descartar(Recordatorio $recordatorio): JsonResponse
    {
        $this->autorizarRecordatorio($recordatorio);
        $recordatorio->update(['estado' => 'descartado']);
        return response()->json(['success' => true]);
    }

    public function renovar(Recordatorio $recordatorio): JsonResponse
    {
        $this->autorizarRecordatorio($recordatorio);
        $recordatorio->update([
            'fecha_vencimiento' => $recordatorio->fecha_vencimiento->addYear(),
            'estado'            => 'pendiente',
        ]);
        return response()->json(['success' => true]);
    }

    public function restaurar(Recordatorio $recordatorio): JsonResponse
    {
        $this->autorizarRecordatorio($recordatorio);
        $recordatorio->update(['estado' => 'pendiente']);
        return response()->json(['success' => true]);
    }
}

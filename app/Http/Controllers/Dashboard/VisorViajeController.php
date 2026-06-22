<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EstadoReserva;
use App\Models\OperadorViaje;
use App\Models\Reserva;
use App\Models\TipoTransporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisorViajeController extends Controller
{
    public function index(Request $request)
    {
        $hogarId = Auth::user()->persona?->hogar_id;

        $query = Reserva::with([
            'viaje',
            'operadorViaje.empresa',
            'operadorViaje.tipoOperadorViaje',
            'tipoReserva',
            'tipoTransporte',
            'estadoReserva',
            'moneda',
        ])->whereHas('viaje', fn($q) => $q->where('hogar_id', $hogarId));

        if ($request->filled('operador_viaje_id')) {
            $query->where('operador_viaje_id', $request->operador_viaje_id);
        }
        if ($request->filled('tipo_transporte_id')) {
            $query->where('tipo_transporte_id', $request->tipo_transporte_id);
        }
        if ($request->filled('estado_reserva_id')) {
            $query->where('estado_reserva_id', $request->estado_reserva_id);
        }
        if ($request->filled('desde')) {
            $query->whereDate('fecha_inicio', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha_inicio', '<=', $request->hasta);
        }

        $reservas = $query->orderBy('fecha_inicio')->get();

        // ── Resumen ────────────────────────────────────────────────────────────
        $totalReservas   = $reservas->count();
        $totalOperadores = $reservas->pluck('operador_viaje_id')->filter()->unique()->count();
        $totalViajes     = $reservas->pluck('viaje_id')->filter()->unique()->count();

        $totalPorMoneda = [];
        foreach ($reservas->whereNotNull('monto')->whereNotNull('moneda_id') as $r) {
            $mid = $r->moneda_id;
            if (!isset($totalPorMoneda[$mid])) {
                $totalPorMoneda[$mid] = [
                    'moneda' => $r->moneda,
                    'total'  => 0,
                ];
            }
            $totalPorMoneda[$mid]['total'] += (float) $r->monto;
        }

        // ── Agrupación ─────────────────────────────────────────────────────────
        $agrupar = in_array($request->get('agrupar'), ['operador', 'transporte', 'ninguno'])
            ? $request->get('agrupar')
            : 'operador';

        $grupos = [];
        if ($agrupar === 'operador') {
            foreach ($reservas as $r) {
                $key = $r->operador_viaje_id ?? '__sin_operador__';
                if (!isset($grupos[$key])) {
                    $grupos[$key] = [
                        'label'        => $r->operadorViaje?->nombre_comercial_resuelto ?? 'Sin operador',
                        'logo'         => $r->operadorViaje?->logo_resuelto,
                        'icono'        => $r->operadorViaje?->tipoOperadorViaje?->icono ?? 'business',
                        'operador_id'  => $r->operador_viaje_id,
                        'reservas'     => [],
                        'subtotales'   => [],
                    ];
                }
                $grupos[$key]['reservas'][] = $r;
                if ($r->monto !== null && $r->moneda_id) {
                    $mid = $r->moneda_id;
                    if (!isset($grupos[$key]['subtotales'][$mid])) {
                        $grupos[$key]['subtotales'][$mid] = ['moneda' => $r->moneda, 'total' => 0];
                    }
                    $grupos[$key]['subtotales'][$mid]['total'] += (float) $r->monto;
                }
            }
        } elseif ($agrupar === 'transporte') {
            foreach ($reservas as $r) {
                $key = $r->tipo_transporte_id ?? '__sin_transporte__';
                if (!isset($grupos[$key])) {
                    $grupos[$key] = [
                        'label'      => $r->tipoTransporte?->nombre ?? 'Sin transporte',
                        'icono'      => $r->tipoTransporte?->icono ?? 'directions_car',
                        'reservas'   => [],
                        'subtotales' => [],
                    ];
                }
                $grupos[$key]['reservas'][] = $r;
                if ($r->monto !== null && $r->moneda_id) {
                    $mid = $r->moneda_id;
                    if (!isset($grupos[$key]['subtotales'][$mid])) {
                        $grupos[$key]['subtotales'][$mid] = ['moneda' => $r->moneda, 'total' => 0];
                    }
                    $grupos[$key]['subtotales'][$mid]['total'] += (float) $r->monto;
                }
            }
        }

        // ── Listas para filtros Select2 ────────────────────────────────────────
        $operadoresSelect = OperadorViaje::with(['empresa', 'tipoOperadorViaje'])
            ->whereHas('reservas.viaje', fn($q) => $q->where('hogar_id', $hogarId))
            ->get();

        $tiposTransporte = TipoTransporte::where('activo', true)->orderBy('nombre')->get();
        $estadosReserva  = EstadoReserva::where('activo', true)->orderBy('nombre')->get();

        // Arrays simples para @json en la vista (sin closures)
        $operadoresArr = $operadoresSelect->map(fn($op) => [
            'id'    => $op->id,
            'text'  => $op->nombre_comercial_resuelto ?? '—',
            'logo'  => $op->logo_resuelto,
            'icono' => $op->tipoOperadorViaje?->icono,
            'ruc'   => $op->empresa?->ruc,
        ])->values()->all();

        $tiposTransporteArr = $tiposTransporte->map(fn($t) => [
            'id'    => $t->id,
            'text'  => $t->nombre,
            'icono' => $t->icono,
        ])->values()->all();

        $estadosReservaArr = $estadosReserva->map(fn($e) => [
            'id'    => $e->id,
            'text'  => $e->nombre,
            'icono' => $e->icono,
            'color' => $e->color,
        ])->values()->all();

        return view('dashboard.visor-viajes.index', compact(
            'reservas',
            'grupos',
            'agrupar',
            'totalReservas',
            'totalOperadores',
            'totalViajes',
            'totalPorMoneda',
            'operadoresSelect',
            'tiposTransporte',
            'estadosReserva',
            'operadoresArr',
            'tiposTransporteArr',
            'estadosReservaArr',
        ));
    }
}

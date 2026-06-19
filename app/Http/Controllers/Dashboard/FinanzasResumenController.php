<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DocumentoFinanciero;
use App\Models\Prestatario;
use App\Models\ProductoFinanciero;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FinanzasResumenController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(): View
    {
        $hogarId = $this->hogarId();
        $today   = Carbon::today();

        // KPI 1: saldo de productos con naturaleza=activo, agrupado por moneda
        $activosPorMoneda = ProductoFinanciero::whereHas(
                'miembro', fn ($q) => $q->where('hogar_id', $hogarId)
            )
            ->whereHas('tipoProductoFinanciero', fn ($q) => $q->where('naturaleza', 'activo'))
            ->join('monedas', 'productos_financieros.moneda_id', '=', 'monedas.id')
            ->select(
                'monedas.codigo',
                'monedas.simbolo',
                DB::raw('SUM(productos_financieros.saldo_actual) as total')
            )
            ->groupBy('monedas.id', 'monedas.codigo', 'monedas.simbolo')
            ->orderByDesc(DB::raw('SUM(productos_financieros.saldo_actual)'))
            ->get();

        // KPI 2: saldo de productos con naturaleza=pasivo, agrupado por moneda
        $pasivosPorMoneda = ProductoFinanciero::whereHas(
                'miembro', fn ($q) => $q->where('hogar_id', $hogarId)
            )
            ->whereHas('tipoProductoFinanciero', fn ($q) => $q->where('naturaleza', 'pasivo'))
            ->join('monedas', 'productos_financieros.moneda_id', '=', 'monedas.id')
            ->select(
                'monedas.codigo',
                'monedas.simbolo',
                DB::raw('SUM(productos_financieros.saldo_actual) as total')
            )
            ->groupBy('monedas.id', 'monedas.codigo', 'monedas.simbolo')
            ->orderByDesc(DB::raw('SUM(productos_financieros.saldo_actual)'))
            ->get();

        // KPI 4: préstamos personales pendientes (te deben), usando accessor saldo
        $prestatarios = Prestatario::where('hogar_id', $hogarId)
            ->with(['movimientosPrestamo', 'moneda'])
            ->get();

        $prestamosPorMoneda = $prestatarios
            ->filter(fn ($p) => $p->saldo > 0)
            ->groupBy(fn ($p) => $p->moneda?->codigo ?? 'OTRO')
            ->map(fn ($grupo, $codigo) => (object) [
                'codigo'  => $codigo,
                'simbolo' => $grupo->first()->moneda?->simbolo ?? $codigo,
                'total'   => $grupo->sum->saldo,
            ])
            ->values();

        // Tabla: top 10 productos ordenados por saldo_actual desc
        $topProductos = ProductoFinanciero::whereHas(
                'miembro', fn ($q) => $q->where('hogar_id', $hogarId)
            )
            ->with(['entidadFinanciera.empresa', 'tipoProductoFinanciero', 'estadoProducto', 'moneda'])
            ->orderByDesc('saldo_actual')
            ->limit(10)
            ->get();

        $totalProductos = ProductoFinanciero::whereHas(
            'miembro', fn ($q) => $q->where('hogar_id', $hogarId)
        )->count();

        // Próximos vencimientos: productos con fecha_vencimiento futura
        $productosVenc = ProductoFinanciero::whereHas(
                'miembro', fn ($q) => $q->where('hogar_id', $hogarId)
            )
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '>=', $today)
            ->with(['tipoProductoFinanciero'])
            ->orderBy('fecha_vencimiento')
            ->limit(10)
            ->get()
            ->map(fn ($p) => (object) [
                'descripcion' => $p->alias,
                'tipo'        => $p->tipoProductoFinanciero?->nombre ?? 'Producto',
                'icono'       => $p->tipoProductoFinanciero?->icono ?? 'savings',
                'fecha'       => $p->fecha_vencimiento,
                'dias'        => (int) $today->diffInDays($p->fecha_vencimiento),
                'enlace'      => route('dashboard.productos-financieros.show', $p),
            ]);

        // Próximos vencimientos: documentos con fecha_vencimiento futura
        $documentosVenc = DocumentoFinanciero::where('hogar_id', $hogarId)
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '>=', $today)
            ->with(['tipoDocumentoFinanciero'])
            ->orderBy('fecha_vencimiento')
            ->limit(10)
            ->get()
            ->map(fn ($d) => (object) [
                'descripcion' => $d->titulo,
                'tipo'        => $d->tipoDocumentoFinanciero?->nombre ?? 'Documento',
                'icono'       => 'description',
                'fecha'       => $d->fecha_vencimiento,
                'dias'        => (int) $today->diffInDays($d->fecha_vencimiento),
                'enlace'      => null,
            ]);

        $vencimientos = $productosVenc->concat($documentosVenc)
            ->sortBy('fecha')
            ->take(10)
            ->values();

        return view('dashboard.finanzas.resumen', compact(
            'activosPorMoneda',
            'pasivosPorMoneda',
            'prestamosPorMoneda',
            'topProductos',
            'totalProductos',
            'vencimientos',
        ));
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\DocumentoLegal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DocumentoViajeroPanelController extends Controller
{
    private const DIAS_AVISO = 90;

    public function index(Request $request)
    {
        $hogarId = Auth::user()->persona?->hogar_id;

        $documentos = DocumentoLegal::with([
            'tipoDocumentoLegal',
            'hogarMiembro.user.persona',
            'estadoDocumentoLegal',
        ])
        ->where('hogar_id', $hogarId)
        ->where('activo', true)
        ->whereHas('tipoDocumentoLegal', fn($q) => $q->where('relevante_viaje', true))
        ->orderBy('fecha_vencimiento')
        ->get();

        $hoy   = now()->startOfDay();
        $aviso = now()->addDays(self::DIAS_AVISO)->startOfDay();

        foreach ($documentos as $doc) {
            if ($doc->fecha_vencimiento === null) {
                $doc->_estado_venc = 'sin_vencimiento';
            } elseif ($doc->fecha_vencimiento->lt($hoy)) {
                $doc->_estado_venc = 'vencido';
            } elseif ($doc->fecha_vencimiento->lte($aviso)) {
                $doc->_estado_venc = 'por_vencer';
            } else {
                $doc->_estado_venc = 'vigente';
            }
        }

        $totalVencidos   = $documentos->where('_estado_venc', 'vencido')->count();
        $totalPorVencer  = $documentos->where('_estado_venc', 'por_vencer')->count();

        // Agrupar por miembro del hogar
        $porMiembro = $documentos->groupBy('hogar_miembro_id');

        return view('dashboard.viajes.documentos-viajero', compact(
            'porMiembro',
            'totalVencidos',
            'totalPorVencer',
        ));
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\PagoNegocio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoNegocioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        abort_unless($negocio->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'entidad_receptora_id'  => ['nullable', 'exists:empresas,id'],
            'tipo_pago_negocio_id'  => ['nullable', 'exists:tipo_pago_negocio,id'],
            'monto'                 => ['required', 'numeric', 'min:0'],
            'moneda_id'             => ['nullable', 'exists:monedas,id'],
            'fecha_pago'            => ['required', 'date'],
            'periodo'               => ['nullable', 'string', 'max:20'],
            'observaciones'         => ['nullable', 'string'],
        ]);

        $data['negocio_id'] = $negocio->id;
        PagoNegocio::create($data);

        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Pago registrado correctamente.')
            ->with('activeTab', 'tabPagos');
    }

    public function destroy(PagoNegocio $pagoNegocio): RedirectResponse
    {
        $negocio = $pagoNegocio->negocio;
        abort_unless($negocio?->hogar_id === $this->hogarId(), 403);
        $pagoNegocio->delete();
        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Pago eliminado.')
            ->with('activeTab', 'tabPagos');
    }
}

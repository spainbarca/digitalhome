<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        abort_unless($negocio->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'proveedor_negocio_id' => ['required', 'exists:proveedores_negocio,id'],
            'numero'               => ['nullable', 'string', 'max:100'],
            'descripcion'          => ['required', 'string'],
            'fecha'                => ['required', 'date'],
            'moneda_id'            => ['nullable', 'exists:monedas,id'],
            'total'                => ['nullable', 'numeric', 'min:0'],
            'observaciones'        => ['nullable', 'string'],
        ]);

        $data['negocio_id'] = $negocio->id;
        Pedido::create($data);

        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Pedido registrado correctamente.')
            ->with('activeTab', 'tabPedidos');
    }

    public function destroy(Pedido $pedido): RedirectResponse
    {
        $negocio = $pedido->negocio;
        abort_unless($negocio?->hogar_id === $this->hogarId(), 403);
        $pedido->delete();
        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Pedido eliminado.')
            ->with('activeTab', 'tabPedidos');
    }
}

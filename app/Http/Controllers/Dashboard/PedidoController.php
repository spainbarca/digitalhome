<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\UpdatePedidoRequest;
use App\Models\Moneda;
use App\Models\Negocio;
use App\Models\Pedido;
use App\Models\ProveedorNegocio;
use App\Models\TipoDocumentoNegocio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PedidoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    // ── Módulo standalone ─────────────────────────────────────────────────────

    public function index(): View
    {
        $hogarId = $this->hogarId();

        $pedidos = Pedido::whereHas('negocio', fn($q) => $q->where('hogar_id', $hogarId))
            ->with(['negocio.empresa', 'proveedorNegocio.empresa', 'moneda', 'documentosNegocio'])
            ->orderByDesc('fecha')
            ->get();

        $negocios    = Negocio::where('hogar_id', $hogarId)->orderBy('nombre')->get();
        $proveedores = ProveedorNegocio::where('hogar_id', $hogarId)->with('empresa')->orderBy('created_at')->get();

        return view('dashboard.pedidos.index', compact('pedidos', 'negocios', 'proveedores'));
    }

    public function create(): View
    {
        $hogarId = $this->hogarId();

        $negocios    = Negocio::where('hogar_id', $hogarId)->orderBy('nombre')->get();
        $proveedores = ProveedorNegocio::where('hogar_id', $hogarId)->with('empresa')->orderBy('created_at')->get();
        $monedas     = Moneda::orderBy('codigo')->get();

        return view('dashboard.pedidos.create', compact('negocios', 'proveedores', 'monedas'));
    }

    public function storeModulo(StorePedidoRequest $request): RedirectResponse
    {
        $hogarId = $this->hogarId();
        $data    = $request->validated();

        $negocio = Negocio::findOrFail($data['negocio_id']);
        abort_unless($negocio->hogar_id === $hogarId, 403);

        $pedido = Pedido::create($data);

        return redirect()->route('dashboard.pedidos.show', $pedido)
            ->with('success', 'Pedido registrado correctamente.');
    }

    public function show(Pedido $pedido): View
    {
        $hogarId = $this->hogarId();
        abort_unless($pedido->negocio?->hogar_id === $hogarId, 403);

        $pedido->load([
            'negocio.empresa',
            'proveedorNegocio.empresa',
            'moneda',
            'documentosNegocio.tipoDocumentoNegocio',
        ]);

        $tiposDocumentoNegocio = TipoDocumentoNegocio::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();

        return view('dashboard.pedidos.show', compact('pedido', 'tiposDocumentoNegocio'));
    }

    public function edit(Pedido $pedido): View
    {
        $hogarId = $this->hogarId();
        abort_unless($pedido->negocio?->hogar_id === $hogarId, 403);

        $negocios    = Negocio::where('hogar_id', $hogarId)->orderBy('nombre')->get();
        $proveedores = ProveedorNegocio::where('hogar_id', $hogarId)->with('empresa')->orderBy('created_at')->get();
        $monedas     = Moneda::orderBy('codigo')->get();

        return view('dashboard.pedidos.edit', compact('pedido', 'negocios', 'proveedores', 'monedas'));
    }

    public function update(UpdatePedidoRequest $request, Pedido $pedido): RedirectResponse
    {
        $hogarId = $this->hogarId();
        abort_unless($pedido->negocio?->hogar_id === $hogarId, 403);

        $data    = $request->validated();
        $negocio = Negocio::findOrFail($data['negocio_id']);
        abort_unless($negocio->hogar_id === $hogarId, 403);

        $pedido->update($data);

        return redirect()->route('dashboard.pedidos.show', $pedido)
            ->with('success', 'Pedido actualizado correctamente.');
    }

    // ── Contexto negocios/show (ruta anidada) ─────────────────────────────────

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

    public function destroy(Pedido $pedido, Request $request): RedirectResponse
    {
        $negocio = $pedido->negocio;
        abort_unless($negocio?->hogar_id === $this->hogarId(), 403);
        $pedido->delete();

        if ($request->input('_from') === 'modulo') {
            return redirect()->route('dashboard.pedidos.index')
                ->with('success', 'Pedido eliminado.');
        }

        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Pedido eliminado.')
            ->with('activeTab', 'tabPedidos');
    }
}

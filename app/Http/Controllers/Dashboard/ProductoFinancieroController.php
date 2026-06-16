<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoFinancieroRequest;
use App\Http\Requests\UpdateProductoFinancieroRequest;
use App\Models\EntidadFinanciera;
use App\Models\EstadoProducto;
use App\Models\HogarMiembro;
use App\Models\Moneda;
use App\Models\ProductoFinanciero;
use App\Models\TipoProductoFinanciero;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductoFinancieroController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function scopedQuery()
    {
        return ProductoFinanciero::whereHas('miembro', fn ($q) => $q->where('hogar_id', $this->hogarId()));
    }

    public function index(Request $request): View
    {
        $tipoId   = $request->get('tipo_id', '');
        $estadoId = $request->get('estado_id', '');
        $search   = $request->get('search', '');

        $productos = $this->scopedQuery()
            ->with(['entidadFinanciera.empresa', 'tipoProductoFinanciero', 'estadoProducto', 'moneda'])
            ->when($search, fn ($q) => $q->where('alias', 'like', "%{$search}%"))
            ->when($tipoId, fn ($q) => $q->where('tipo_producto_financiero_id', $tipoId))
            ->when($estadoId, fn ($q) => $q->where('estado_producto_id', $estadoId))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $tipos   = TipoProductoFinanciero::where('activo', true)->orderBy('nombre')->get();
        $estados = EstadoProducto::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.productos-financieros.index', compact('productos', 'tipos', 'estados', 'tipoId', 'estadoId', 'search'));
    }

    public function create(): View
    {
        return view('dashboard.productos-financieros.create', $this->formData());
    }

    public function store(StoreProductoFinancieroRequest $request): RedirectResponse
    {
        $producto = ProductoFinanciero::create($request->validated());

        return redirect()->route('dashboard.productos-financieros.show', $producto)
            ->with('success', 'Producto financiero registrado correctamente.');
    }

    public function show(ProductoFinanciero $productoFinanciero): View
    {
        abort_unless($productoFinanciero->miembro?->hogar_id === $this->hogarId(), 403);

        $productoFinanciero->load([
            'entidadFinanciera.empresa',
            'tipoProductoFinanciero',
            'estadoProducto',
            'moneda',
            'miembro.user.persona',
            'productoPadre.entidadFinanciera',
        ]);

        return view('dashboard.productos-financieros.show', compact('productoFinanciero'));
    }

    public function edit(ProductoFinanciero $productoFinanciero): View
    {
        abort_unless($productoFinanciero->miembro?->hogar_id === $this->hogarId(), 403);

        return view('dashboard.productos-financieros.edit', array_merge(
            $this->formData($productoFinanciero),
            ['productoFinanciero' => $productoFinanciero]
        ));
    }

    public function update(UpdateProductoFinancieroRequest $request, ProductoFinanciero $productoFinanciero): RedirectResponse
    {
        abort_unless($productoFinanciero->miembro?->hogar_id === $this->hogarId(), 403);

        $productoFinanciero->update($request->validated());

        return redirect()->route('dashboard.productos-financieros.show', $productoFinanciero)
            ->with('success', 'Producto financiero actualizado correctamente.');
    }

    public function destroy(ProductoFinanciero $productoFinanciero): RedirectResponse
    {
        abort_unless($productoFinanciero->miembro?->hogar_id === $this->hogarId(), 403);

        $productoFinanciero->delete();

        return redirect()->route('dashboard.productos-financieros.index')
            ->with('success', 'Producto financiero eliminado correctamente.');
    }

    private function formData(?ProductoFinanciero $productoFinanciero = null): array
    {
        $hogarId = $this->hogarId();

        $entidades = EntidadFinanciera::with('empresa')
            ->get()
            ->sortBy('nombre_comercial_resuelto')
            ->values();

        $tipos    = TipoProductoFinanciero::where('activo', true)->orderBy('nombre')->get();
        $estados  = EstadoProducto::where('activo', true)->orderBy('nombre')->get();
        $monedas  = Moneda::orderByDesc('moneda_local')->orderBy('nombre')->get();
        $miembros = HogarMiembro::with('user.persona')
            ->where('hogar_id', $hogarId)
            ->where('estado', 'activo')
            ->orderBy('apodo')
            ->get();

        $productosPadre = ProductoFinanciero::whereHas('miembro', fn ($q) => $q->where('hogar_id', $hogarId))
            ->with(['entidadFinanciera.empresa', 'tipoProductoFinanciero'])
            ->when($productoFinanciero, fn ($q) => $q->where('id', '!=', $productoFinanciero->id))
            ->orderBy('alias')
            ->get();

        return compact('entidades', 'tipos', 'estados', 'monedas', 'miembros', 'productosPadre');
    }
}

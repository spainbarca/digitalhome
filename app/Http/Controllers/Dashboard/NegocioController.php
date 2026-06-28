<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNegocioRequest;
use App\Http\Requests\UpdateNegocioRequest;
use App\Models\DocumentoLegal;
use App\Models\DocumentoNegocio;
use App\Models\Empresa;
use App\Models\EstadoNegocio;
use App\Models\Moneda;
use App\Models\Negocio;
use App\Models\Persona;
use App\Models\ProveedorNegocio;
use App\Models\RegimenTributario;
use App\Models\TipoDocumentoNegocio;
use App\Models\TipoPagoNegocio;
use App\Models\TipoSociedad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NegocioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request): View
    {
        $hogarId = $this->hogarId();
        $search  = $request->get('search', '');

        $negocios = Negocio::where('hogar_id', $hogarId)
            ->with(['empresa.sector', 'regimenTributario', 'tipoSociedad', 'estadoNegocio'])
            ->when($search, fn($q) => $q->where(function ($sq) use ($search) {
                $sq->where('nombre', 'like', "%{$search}%")
                   ->orWhereHas('empresa', fn($eq) =>
                       $eq->where('razon_social', 'like', "%{$search}%")
                          ->orWhere('ruc', 'like', "%{$search}%")
                   );
            }))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.negocios.index', compact('negocios', 'search'));
    }

    public function create(): View
    {
        $hogarId         = $this->hogarId();
        $empresas        = Empresa::where('activo', true)->with('sector')->orderBy('razon_social')->get();
        $personas        = Persona::where('hogar_id', $hogarId)->orderBy('nombres')->get();
        $regimenes       = RegimenTributario::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $tiposSociedad   = TipoSociedad::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $estadosNegocio  = EstadoNegocio::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();

        return view('dashboard.negocios.create', compact(
            'empresas', 'personas', 'regimenes', 'tiposSociedad', 'estadosNegocio'
        ));
    }

    public function store(StoreNegocioRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['hogar_id'] = $this->hogarId();

        $negocio = Negocio::create($data);

        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Negocio registrado correctamente.');
    }

    public function show(Negocio $negocio): View
    {
        $hogarId = $this->hogarId();
        abort_unless($negocio->hogar_id === $hogarId, 403);

        $negocio->load([
            'empresa.sector',
            'regimenTributario',
            'tipoSociedad',
            'estadoNegocio',
            'productosFinancieros.tipoProductoFinanciero',
            'productosFinancieros.entidadFinanciera',
            'productosFinancieros.moneda',
            'pedidos.proveedorNegocio.empresa',
            'pedidos.moneda',
            'pedidos.documentosNegocio.tipoDocumentoNegocio',
            'pagosNegocio.entidadReceptora',
            'pagosNegocio.tipoPagoNegocio',
            'pagosNegocio.moneda',
            'pagosNegocio.documentosNegocio.tipoDocumentoNegocio',
            'documentosNegocio.tipoDocumentoNegocio',
        ]);

        $representante = $negocio->miembro_id ? Persona::find($negocio->miembro_id) : null;

        // Para modales
        $proveedores          = ProveedorNegocio::where('hogar_id', $hogarId)->with('empresa')->orderBy('created_at')->get();
        $monedas              = Moneda::orderBy('codigo')->get();
        $tiposPagoNegocio     = TipoPagoNegocio::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $tiposDocumentoNegocio = TipoDocumentoNegocio::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $empresas             = Empresa::where('activo', true)->orderBy('razon_social')->get();

        // Documentos legales relevantes para negocios (hogar-scoped, read-only)
        $documentosLegalesRelevantes = DocumentoLegal::where('hogar_id', $hogarId)
            ->whereHas('tipoDocumentoLegal', fn($q) => $q->where('relevante_negocio', true))
            ->with(['tipoDocumentoLegal', 'estadoDocumentoLegal'])
            ->orderBy('fecha_vencimiento')
            ->get();

        // Todos los documentos_negocio del negocio (directos + de pedidos + de pagos)
        $pedidoIds = $negocio->pedidos->pluck('id');
        $pagoIds   = $negocio->pagosNegocio->pluck('id');

        $todosLosDocumentos = DocumentoNegocio::where(function ($q) use ($negocio, $pedidoIds, $pagoIds) {
                $q->where('negocio_id', $negocio->id)
                  ->orWhereIn('pedido_id', $pedidoIds)
                  ->orWhereIn('pago_negocio_id', $pagoIds);
            })
            ->with(['tipoDocumentoNegocio', 'pedido.proveedorNegocio', 'pagoNegocio.tipoPagoNegocio'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return view('dashboard.negocios.show', compact(
            'negocio',
            'representante',
            'proveedores',
            'monedas',
            'tiposPagoNegocio',
            'tiposDocumentoNegocio',
            'empresas',
            'documentosLegalesRelevantes',
            'todosLosDocumentos',
        ));
    }

    public function edit(Negocio $negocio): View
    {
        abort_unless($negocio->hogar_id === $this->hogarId(), 403);

        $hogarId         = $this->hogarId();
        $empresas        = Empresa::where('activo', true)->with('sector')->orderBy('razon_social')->get();
        $personas        = Persona::where('hogar_id', $hogarId)->orderBy('nombres')->get();
        $regimenes       = RegimenTributario::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $tiposSociedad   = TipoSociedad::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();
        $estadosNegocio  = EstadoNegocio::where('activo', true)->orderBy('orden')->orderBy('nombre')->get();

        return view('dashboard.negocios.edit', compact(
            'negocio', 'empresas', 'personas', 'regimenes', 'tiposSociedad', 'estadosNegocio'
        ));
    }

    public function update(UpdateNegocioRequest $request, Negocio $negocio): RedirectResponse
    {
        abort_unless($negocio->hogar_id === $this->hogarId(), 403);

        $negocio->update($request->validated());

        return redirect()->route('dashboard.negocios.show', $negocio)
            ->with('success', 'Negocio actualizado correctamente.');
    }

    public function destroy(Negocio $negocio): RedirectResponse
    {
        abort_unless($negocio->hogar_id === $this->hogarId(), 403);
        $negocio->delete();
        return redirect()->route('dashboard.negocios.index')
            ->with('success', 'Negocio eliminado correctamente.');
    }
}

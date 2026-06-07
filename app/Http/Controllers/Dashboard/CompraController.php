<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompraRequest;
use App\Http\Requests\UpdateCompraRequest;
use App\Models\CategoriaCompra;
use App\Models\Comercio;
use App\Models\Compra;
use App\Models\HogarMiembro;
use App\Models\MetodoPago;
use App\Models\Moneda;
use App\Models\TipoDocumentoCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request)
    {
        $hogarId = $this->hogarId();

        $applyFilters = function ($q) use ($request) {
            if ($request->filled('fecha_desde'))    $q->where('fecha_compra', '>=', $request->fecha_desde);
            if ($request->filled('fecha_hasta'))    $q->where('fecha_compra', '<=', $request->fecha_hasta);
            if ($request->filled('categoria_id'))   $q->where('categoria_compra_id', $request->categoria_id);
            if ($request->filled('comercio_id'))    $q->where('comercio_id', $request->comercio_id);
            if ($request->filled('miembro_id'))     $q->where('miembro_id', $request->miembro_id);
            if ($request->filled('metodo_pago_id')) $q->where('metodo_pago_id', $request->metodo_pago_id);
        };

        $q1 = Compra::where('hogar_id', $hogarId);
        $applyFilters($q1);
        $totalFiltrado = $q1->sum('total');

        $q2 = Compra::where('hogar_id', $hogarId);
        $applyFilters($q2);
        $compras = $q2
            ->with(['comercio.empresa', 'categoriaCompra', 'metodoPago', 'moneda',
                    'miembro.user.persona', 'documentos.tipoDocumentoCompra'])
            ->orderByDesc('fecha_compra')
            ->paginate(20)
            ->withQueryString();

        $categorias  = CategoriaCompra::orderBy('nombre')->get();
        $comercios   = Comercio::where('hogar_id', $hogarId)->with('empresa')->orderBy('nombre_referencial')->get();
        $miembros    = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $metodosPago = MetodoPago::orderBy('nombre')->get();

        return view('dashboard.compras.index', compact(
            'compras', 'totalFiltrado', 'categorias', 'comercios', 'miembros', 'metodosPago'
        ));
    }

    public function create()
    {
        $hogarId     = $this->hogarId();
        $miembros    = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $comercios   = Comercio::where('hogar_id', $hogarId)->with('empresa')->orderBy('nombre_referencial')->get();
        $categorias  = CategoriaCompra::where('activo', true)->orderBy('nombre')->get();
        $metodosPago = MetodoPago::where('activo', true)->orderBy('nombre')->get();
        $monedas     = Moneda::orderBy('nombre')->get();
        $monedaLocal = Moneda::where('moneda_local', true)->first();

        return view('dashboard.compras.create', compact(
            'miembros', 'comercios', 'categorias', 'metodosPago', 'monedas', 'monedaLocal'
        ));
    }

    public function store(StoreCompraRequest $request)
    {
        $data             = $request->validated();
        $data['hogar_id'] = $this->hogarId();
        $compra           = Compra::create($data);

        return redirect()->route('dashboard.compras.show', $compra)
            ->with('success', 'Compra registrada correctamente.');
    }

    public function show(Compra $compra)
    {
        abort_unless($compra->hogar_id === $this->hogarId(), 403);

        $compra->load([
            'comercio.empresa',
            'categoriaCompra',
            'metodoPago',
            'moneda',
            'miembro.user.persona',
            'documentos.tipoDocumentoCompra',
        ]);

        $tiposDocumento = TipoDocumentoCompra::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.compras.show', compact('compra', 'tiposDocumento'));
    }

    public function edit(Compra $compra)
    {
        abort_unless($compra->hogar_id === $this->hogarId(), 403);

        $hogarId     = $this->hogarId();
        $miembros    = HogarMiembro::where('hogar_id', $hogarId)->with('user.persona')->get();
        $comercios   = Comercio::where('hogar_id', $hogarId)->with('empresa')->orderBy('nombre_referencial')->get();
        $categorias  = CategoriaCompra::where('activo', true)->orderBy('nombre')->get();
        $metodosPago = MetodoPago::where('activo', true)->orderBy('nombre')->get();
        $monedas     = Moneda::orderBy('nombre')->get();

        return view('dashboard.compras.edit', compact(
            'compra', 'miembros', 'comercios', 'categorias', 'metodosPago', 'monedas'
        ));
    }

    public function update(UpdateCompraRequest $request, Compra $compra)
    {
        abort_unless($compra->hogar_id === $this->hogarId(), 403);

        $compra->update($request->validated());

        return redirect()->route('dashboard.compras.show', $compra)
            ->with('success', 'Compra actualizada correctamente.');
    }

    public function destroy(Compra $compra)
    {
        abort_unless($compra->hogar_id === $this->hogarId(), 403);

        $compra->delete();

        return redirect()->route('dashboard.compras.index')
            ->with('success', 'Compra eliminada correctamente.');
    }
}

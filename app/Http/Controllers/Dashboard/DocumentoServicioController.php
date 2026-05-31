<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentoServicioRequest;
use App\Http\Requests\UpdateDocumentoServicioRequest;
use App\Models\CuentaServicio;
use App\Models\DocumentoServicio;
use App\Models\EstadoPago;
use App\Models\Etiqueta;
use App\Models\HistorialVersion;
use App\Models\MetodoPago;
use App\Models\Moneda;
use App\Models\PropiedadInmueble;
use App\Models\TipoDocumentoServicio;
use App\Models\TipoVisibilidad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentoServicioController extends Controller
{
    private function propiedadesHogar(): \Illuminate\Database\Eloquent\Collection
    {
        $hogarId = Auth::user()->persona?->hogar_id;
        return PropiedadInmueble::whereHas('persona', fn ($q) => $q->where('hogar_id', $hogarId))
            ->with('tipoInmueble')
            ->orderBy('alias')
            ->get();
    }

    private function autorizarDocumento(DocumentoServicio $documento): void
    {
        $hogarId = Auth::user()->persona?->hogar_id;
        $documento->loadMissing(['cuenta.propiedad.persona']);
        abort_unless($documento->cuenta?->propiedad?->persona?->hogar_id === $hogarId, 403);
    }

    public function index(Request $request): View
    {
        $propiedades = $this->propiedadesHogar();

        $propiedadId           = $request->get('propiedad');
        $propiedadSeleccionada = $propiedadId ? $propiedades->firstWhere('id', $propiedadId) : null;

        $cuentas            = collect();
        $cuentaSeleccionada = null;
        $documentos         = collect();

        if ($propiedadSeleccionada) {
            $cuentas = CuentaServicio::where('propiedad_id', $propiedadSeleccionada->id)
                ->with(['proveedor.tipoServicio', 'proveedor.empresa'])
                ->orderBy('numero_cuenta')
                ->get();

            $cuentaId           = $request->get('cuenta');
            $cuentaSeleccionada = $cuentaId ? $cuentas->firstWhere('id', $cuentaId) : null;

            if ($cuentaSeleccionada) {
                $documentos = DocumentoServicio::where('cuenta_id', $cuentaSeleccionada->id)
                    ->with(['cuenta.proveedor.tipoServicio', 'cuenta.proveedor.empresa', 'tipoDocumento', 'estadoPago', 'metodoPago', 'moneda', 'subidoPor'])
                    ->latest('periodo_inicio')
                    ->paginate(12)
                    ->withQueryString();
            }
        }

        return view('dashboard.documentos-servicio.index', compact(
            'propiedades', 'propiedadSeleccionada', 'cuentas', 'cuentaSeleccionada', 'documentos'
        ));
    }

    public function create(Request $request): View
    {
        $propiedades        = $this->propiedadesHogar();
        $hogarId            = Auth::user()->persona?->hogar_id;
        $cuentaSeleccionada = null;
        $propiedadSeleccionada = null;
        $cuentas            = collect();

        if ($cuentaId = $request->get('cuenta')) {
            $cuentaSeleccionada = CuentaServicio::whereHas('propiedad.persona', fn ($q) => $q->where('hogar_id', $hogarId))
                ->with(['proveedor.tipoServicio.unidadMedida', 'propiedad'])
                ->find($cuentaId);
            if ($cuentaSeleccionada) {
                $propiedadSeleccionada = $propiedades->firstWhere('id', $cuentaSeleccionada->propiedad_id);
            }
        } elseif ($propiedadId = $request->get('propiedad')) {
            $propiedadSeleccionada = $propiedades->firstWhere('id', $propiedadId);
        }

        if ($propiedadSeleccionada) {
            $cuentas = CuentaServicio::where('propiedad_id', $propiedadSeleccionada->id)
                ->with(['proveedor.tipoServicio.unidadMedida'])
                ->orderBy('numero_cuenta')
                ->get();
        }

        $cuentasConUnidad = $cuentas->mapWithKeys(function ($c) {
            return [$c->id => [
                'simbolo' => $c->proveedor?->tipoServicio?->unidadMedida?->simbolo ?? '',
                'nombre'  => $c->proveedor?->tipoServicio?->unidadMedida?->nombre ?? '',
            ]];
        });

        $cuentasJson = $cuentas->map(function ($c) {
            return [
                'id'           => $c->id,
                'numero'       => $c->numero_cuenta,
                'propiedad_id' => $c->propiedad_id,
                'tipo_nombre'  => optional($c->proveedor?->tipoServicio)->nombre ?? '',
                'tipo_icono'   => optional($c->proveedor?->tipoServicio)->icono ?? 'receipt',
            ];
        })->values();

        $tiposDocumento = TipoDocumentoServicio::orderBy('nombre')->get();
        $estadosPago    = EstadoPago::orderBy('nombre')->get();
        $metodosPago    = MetodoPago::where('activo', true)->orderBy('nombre')->get();
        $monedas        = Moneda::orderBy('nombre')->get();
        $visibilidades  = TipoVisibilidad::orderBy('nombre')->get();

        $tiposDocumentoJson = $tiposDocumento->map(function ($t) {
            return ['id' => $t->id, 'nombre' => $t->nombre, 'icono' => $t->icono ?? 'description'];
        })->values();

        $visibilidadesJson = $visibilidades->map(function ($v) {
            return ['id' => $v->id, 'nombre' => $v->nombre, 'icono' => $v->icono ?? 'visibility'];
        })->values();

        $metodosPagoJson = $metodosPago->map(function ($m) {
            return ['id' => $m->id, 'nombre' => $m->nombre, 'icono' => $m->icono ?? 'payment', 'logo' => $m->logo ? asset('storage/' . $m->logo) : null];
        })->values();

        $estadosPagoJson = $estadosPago->map(function ($e) {
            return ['id' => $e->id, 'nombre' => $e->nombre, 'color' => $e->color ?? '#6b7280'];
        })->values();

        return view('dashboard.documentos-servicio.create', compact(
            'propiedades', 'propiedadSeleccionada', 'cuentas', 'cuentaSeleccionada',
            'cuentasJson', 'tiposDocumento', 'estadosPago', 'metodosPago', 'monedas', 'visibilidades',
            'tiposDocumentoJson', 'visibilidadesJson', 'metodosPagoJson', 'estadosPagoJson',
            'cuentasConUnidad'
        ));
    }

    public function store(StoreDocumentoServicioRequest $request): RedirectResponse
    {
        $hogarId = Auth::user()->persona?->hogar_id;

        $cuenta = CuentaServicio::with('propiedad.persona')->findOrFail($request->cuenta_id);
        abort_unless($cuenta->propiedad?->persona?->hogar_id === $hogarId, 403);

        $data = $request->validated();
        unset($data['archivo'], $data['documento'], $data['documento_extension'], $data['documento_tamano_bytes']);

        $file = $request->file('archivo');
        $path = $file->store('documentos-servicio', 'public');

        $data['archivo_url']  = $path;
        $data['extension']    = strtolower($file->getClientOriginalExtension());
        $data['tamano_bytes'] = $file->getSize();
        $data['subido_por']   = Auth::id();
        $data['hogar_id']     = Auth::user()->persona?->hogar_id;

        if ($request->hasFile('documento')) {
            $doc = $request->file('documento');
            $docPath = $doc->store('documentos-servicio/oficiales', 'public');
            $data['documento_url']           = $docPath;
            $data['documento_extension']     = strtolower($doc->getClientOriginalExtension());
            $data['documento_tamano_bytes']  = $doc->getSize();
        }

        $documento = DocumentoServicio::create($data);

        if ($request->filled('lectura_anterior') && $request->filled('lectura_actual')) {
            $documento->lecturaConsumo()->create([
                'lectura_anterior' => $request->lectura_anterior,
                'lectura_actual'   => $request->lectura_actual,
                'consumo'          => $request->consumo ?? ($request->lectura_actual - $request->lectura_anterior),
            ]);
        }

        return redirect()->route('dashboard.documentos-servicio.index', [
            'propiedad' => $cuenta->propiedad_id,
            'cuenta'    => $cuenta->id,
        ])->with('success', 'Documento subido correctamente.');
    }

    public function show(DocumentoServicio $documentoServicio): View
    {
        $this->autorizarDocumento($documentoServicio);

        $documentoServicio->load([
            'cuenta.proveedor.tipoServicio.unidadMedida',
            'cuenta.proveedor.empresa',
            'cuenta.propiedad.tipoInmueble',
            'tipoDocumento',
            'estadoPago',
            'metodoPago',
            'moneda',
            'visibilidad',
            'subidoPor.persona',
            'etiquetas',
            'lecturaConsumo',
            'historialVersiones',
        ]);

        $etiquetasDisponibles = Etiqueta::where('hogar_id', Auth::user()->persona?->hogar_id)
            ->orderBy('nombre')->get();

        return view('dashboard.documentos-servicio.show', [
            'documento'            => $documentoServicio,
            'etiquetasDisponibles' => $etiquetasDisponibles,
        ]);
    }

    public function edit(DocumentoServicio $documentoServicio): View
    {
        $this->autorizarDocumento($documentoServicio);

        $documentoServicio->load([
            'cuenta.proveedor.tipoServicio.unidadMedida',
            'cuenta.proveedor.empresa',
            'cuenta.propiedad.tipoInmueble',
            'tipoDocumento', 'estadoPago', 'metodoPago', 'moneda', 'visibilidad',
            'lecturaConsumo',
        ]);

        $tiposDocumento = TipoDocumentoServicio::orderBy('nombre')->get();
        $estadosPago    = EstadoPago::orderBy('nombre')->get();
        $metodosPago    = MetodoPago::where('activo', true)->orderBy('nombre')->get();
        $monedas        = Moneda::orderBy('nombre')->get();
        $visibilidades  = TipoVisibilidad::orderBy('nombre')->get();

        $tiposDocumentoJson = $tiposDocumento->map(function ($t) {
            return ['id' => $t->id, 'nombre' => $t->nombre, 'icono' => $t->icono ?? 'description'];
        })->values();

        $visibilidadesJson = $visibilidades->map(function ($v) {
            return ['id' => $v->id, 'nombre' => $v->nombre, 'icono' => $v->icono ?? 'visibility'];
        })->values();

        $metodosPagoJson = $metodosPago->map(function ($m) {
            return ['id' => $m->id, 'nombre' => $m->nombre, 'icono' => $m->icono ?? 'payment', 'logo' => $m->logo ? asset('storage/' . $m->logo) : null];
        })->values();

        $estadosPagoJson = $estadosPago->map(function ($e) {
            return ['id' => $e->id, 'nombre' => $e->nombre, 'color' => $e->color ?? '#6b7280'];
        })->values();

        $cuentasConUnidad = collect([$documentoServicio->cuenta_id => [
            'simbolo' => $documentoServicio->cuenta?->proveedor?->tipoServicio?->unidadMedida?->simbolo ?? '',
            'nombre'  => $documentoServicio->cuenta?->proveedor?->tipoServicio?->unidadMedida?->nombre ?? '',
        ]]);

        return view('dashboard.documentos-servicio.edit', [
            'documento'          => $documentoServicio,
            'tiposDocumento'     => $tiposDocumento,
            'estadosPago'        => $estadosPago,
            'metodosPago'        => $metodosPago,
            'monedas'            => $monedas,
            'visibilidades'      => $visibilidades,
            'tiposDocumentoJson' => $tiposDocumentoJson,
            'visibilidadesJson'  => $visibilidadesJson,
            'metodosPagoJson'    => $metodosPagoJson,
            'estadosPagoJson'    => $estadosPagoJson,
            'cuentasConUnidad'   => $cuentasConUnidad,
        ]);
    }

    public function update(UpdateDocumentoServicioRequest $request, DocumentoServicio $documentoServicio): RedirectResponse
    {
        $this->autorizarDocumento($documentoServicio);

        $data = $request->validated();
        unset($data['archivo'], $data['documento'], $data['documento_extension'], $data['documento_tamano_bytes']);

        if ($request->hasFile('archivo')) {
            HistorialVersion::create([
                'versionable_id'   => $documentoServicio->id,
                'versionable_type' => DocumentoServicio::class,
                'archivo_url'      => $documentoServicio->archivo_url,
                'extension'        => $documentoServicio->extension,
                'tamano_bytes'     => $documentoServicio->tamano_bytes,
                'subido_por'       => Auth::id(),
            ]);

            $file = $request->file('archivo');
            $path = $file->store('documentos-servicio', 'public');

            $data['archivo_url']  = $path;
            $data['extension']    = strtolower($file->getClientOriginalExtension());
            $data['tamano_bytes'] = $file->getSize();
        }

        if ($request->hasFile('documento')) {
            if ($documentoServicio->documento_url) {
                HistorialVersion::create([
                    'versionable_id'   => $documentoServicio->id,
                    'versionable_type' => DocumentoServicio::class,
                    'archivo_url'      => $documentoServicio->documento_url,
                    'extension'        => $documentoServicio->documento_extension,
                    'tamano_bytes'     => $documentoServicio->documento_tamano_bytes,
                    'subido_por'       => Auth::id(),
                ]);
            }

            $doc     = $request->file('documento');
            $docPath = $doc->store('documentos-servicio/oficiales', 'public');

            $data['documento_url']           = $docPath;
            $data['documento_extension']     = strtolower($doc->getClientOriginalExtension());
            $data['documento_tamano_bytes']  = $doc->getSize();
        }

        $documentoServicio->update($data);

        if ($request->filled('lectura_anterior') && $request->filled('lectura_actual')) {
            $documentoServicio->lecturaConsumo()->updateOrCreate(
                ['documento_id' => $documentoServicio->id],
                [
                    'lectura_anterior' => $request->lectura_anterior,
                    'lectura_actual'   => $request->lectura_actual,
                    'consumo'          => $request->consumo ?? ($request->lectura_actual - $request->lectura_anterior),
                ]
            );
        }

        return redirect()->route('dashboard.documentos-servicio.show', $documentoServicio)
            ->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(DocumentoServicio $documentoServicio): RedirectResponse
    {
        $this->autorizarDocumento($documentoServicio);

        $cuentaId    = $documentoServicio->cuenta_id;
        $propiedadId = $documentoServicio->cuenta->propiedad_id;

        $documentoServicio->delete();

        return redirect()->route('dashboard.documentos-servicio.index', [
            'propiedad' => $propiedadId,
            'cuenta'    => $cuentaId,
        ])->with('success', 'Documento eliminado correctamente.');
    }

    public function marcarPagado(DocumentoServicio $documentoServicio): JsonResponse
    {
        $this->autorizarDocumento($documentoServicio);

        $estadoPagado = EstadoPago::where('nombre', 'like', '%pagado%')->first();

        if (!$estadoPagado) {
            return response()->json(['success' => false, 'message' => 'Estado no encontrado'], 404);
        }

        $documentoServicio->update([
            'estado_pago_id' => $estadoPagado->id,
            'fecha_pago'     => $documentoServicio->fecha_pago ?? today(),
        ]);

        return response()->json(['success' => true]);
    }

    public function asignarEtiqueta(Request $request, DocumentoServicio $documentoServicio): JsonResponse
    {
        $this->autorizarDocumento($documentoServicio);

        $hogarId = Auth::user()->persona?->hogar_id;
        $etiqueta = Etiqueta::where('hogar_id', $hogarId)->findOrFail($request->etiqueta_id);

        // Evitar duplicados
        if (!$documentoServicio->etiquetas()->where('etiqueta_id', $etiqueta->id)->exists()) {
            $documentoServicio->etiquetas()->attach($etiqueta->id);
        }

        return response()->json([
            'success'  => true,
            'etiqueta' => ['id' => $etiqueta->id, 'nombre' => $etiqueta->nombre, 'color' => $etiqueta->color],
        ]);
    }

    public function quitarEtiqueta(Request $request, DocumentoServicio $documentoServicio): JsonResponse
    {
        $this->autorizarDocumento($documentoServicio);

        $documentoServicio->etiquetas()->detach($request->etiqueta_id);

        return response()->json(['success' => true]);
    }
}

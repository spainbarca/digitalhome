<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCuentaServicioRequest;
use App\Http\Requests\UpdateCuentaServicioRequest;
use App\Models\CuentaServicio;
use App\Models\PropiedadInmueble;
use App\Models\ProveedorServicio;
use App\Models\TipoServicio;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CuentaServicioController extends Controller
{
    private function autorizarPropiedad(PropiedadInmueble $propiedad): void
    {
        $hogarId = Auth::user()->persona?->hogar_id;
        abort_unless($propiedad->persona?->hogar_id === $hogarId, 403);
    }

    public function index(PropiedadInmueble $propiedad): View
    {
        $this->authorize('viewAny', CuentaServicio::class);
        $this->autorizarPropiedad($propiedad);

        $cuentas = CuentaServicio::where('propiedad_id', $propiedad->id)
            ->with(['proveedor.tipoServicio'])
            ->latest()
            ->get();

        return view('dashboard.cuentas-servicio.index', compact('propiedad', 'cuentas'));
    }

    public function create(PropiedadInmueble $propiedad): View
    {
        $this->authorize('create', [CuentaServicio::class, $propiedad]);
        $this->autorizarPropiedad($propiedad);

        $tiposServicio = TipoServicio::where('activo', true)->orderBy('nombre')->get();
        $proveedores   = ProveedorServicio::where('activo', true)
            ->with('tipoServicio')
            ->orderBy('nombre_comercial')
            ->get();
        $hogarUsers = User::whereHas('persona', fn ($q) => $q->where('hogar_id', Auth::user()->persona?->hogar_id))
            ->with('persona')
            ->get();
        $proveedoresJson = $proveedores->map(function ($p) {
            return [
                'id'         => $p->id,
                'nombre'     => $p->nombre_comercial ?? '(sin nombre)',
                'tipo'       => $p->tipo_servicio_id,
                'tipo_nombre' => optional($p->tipoServicio)->nombre ?? '',
            ];
        })->values();

        return view('dashboard.cuentas-servicio.create', compact('propiedad', 'tiposServicio', 'proveedores', 'proveedoresJson', 'hogarUsers'));
    }

    public function store(StoreCuentaServicioRequest $request, PropiedadInmueble $propiedad): RedirectResponse
    {
        $this->authorize('create', [CuentaServicio::class, $propiedad]);
        $this->autorizarPropiedad($propiedad);

        CuentaServicio::create([
            'propiedad_id'    => $propiedad->id,
            'proveedor_id'    => $request->proveedor_id,
            'numero_cuenta'   => $request->numero_cuenta,
            'titular_user_id' => $request->titular_user_id,
            'estado'          => $request->estado,
            'fecha_inicio'    => $request->fecha_inicio ?: null,
            'notas'           => $request->notas,
        ]);

        return redirect()->route('dashboard.propiedades.cuentas.index', $propiedad)
            ->with('success', 'Cuenta de servicio registrada correctamente.');
    }

    public function show(PropiedadInmueble $propiedad, CuentaServicio $cuenta): View
    {
        $this->authorize('view', $cuenta);
        abort_unless($cuenta->propiedad_id === $propiedad->id, 404);

        $cuenta->load(['proveedor.tipoServicio', 'titular.persona']);

        return view('dashboard.cuentas-servicio.show', compact('propiedad', 'cuenta'));
    }

    public function edit(PropiedadInmueble $propiedad, CuentaServicio $cuenta): View
    {
        $this->authorize('update', $cuenta);
        abort_unless($cuenta->propiedad_id === $propiedad->id, 404);

        $tiposServicio = TipoServicio::where('activo', true)->orderBy('nombre')->get();
        $proveedores   = ProveedorServicio::where('activo', true)
            ->with('tipoServicio')
            ->orderBy('nombre_comercial')
            ->get();
        $hogarUsers = User::whereHas('persona', fn ($q) => $q->where('hogar_id', Auth::user()->persona?->hogar_id))
            ->with('persona')
            ->get();
        $proveedoresJson = $proveedores->map(function ($p) {
            return [
                'id'         => $p->id,
                'nombre'     => $p->nombre_comercial ?? '(sin nombre)',
                'tipo'       => $p->tipo_servicio_id,
                'tipo_nombre' => optional($p->tipoServicio)->nombre ?? '',
            ];
        })->values();

        return view('dashboard.cuentas-servicio.edit', compact('propiedad', 'cuenta', 'tiposServicio', 'proveedores', 'proveedoresJson', 'hogarUsers'));
    }

    public function update(UpdateCuentaServicioRequest $request, PropiedadInmueble $propiedad, CuentaServicio $cuenta): RedirectResponse
    {
        $this->authorize('update', $cuenta);
        abort_unless($cuenta->propiedad_id === $propiedad->id, 404);

        $cuenta->update([
            'proveedor_id'    => $request->proveedor_id,
            'numero_cuenta'   => $request->numero_cuenta,
            'titular_user_id' => $request->titular_user_id,
            'estado'          => $request->estado,
            'fecha_inicio'    => $request->fecha_inicio ?: null,
            'notas'           => $request->notas,
        ]);

        return redirect()->route('dashboard.propiedades.cuentas.index', $propiedad)
            ->with('success', 'Cuenta de servicio actualizada correctamente.');
    }

    public function destroy(PropiedadInmueble $propiedad, CuentaServicio $cuenta): RedirectResponse
    {
        $this->authorize('delete', $cuenta);
        abort_unless($cuenta->propiedad_id === $propiedad->id, 404);

        $cuenta->delete();

        return redirect()->route('dashboard.propiedades.cuentas.index', $propiedad)
            ->with('success', 'Cuenta de servicio eliminada correctamente.');
    }
}

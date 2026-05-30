<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCuentaServicioRequest;
use App\Models\CuentaServicio;
use App\Models\PropiedadInmueble;
use App\Models\ProveedorServicio;
use App\Models\TipoServicio;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CuentasServicioController extends Controller
{
    private function propiedadesHogar(): \Illuminate\Database\Eloquent\Collection
    {
        $hogarId = Auth::user()->persona?->hogar_id;
        return PropiedadInmueble::whereHas('persona', fn ($q) => $q->where('hogar_id', $hogarId))
            ->with('tipoInmueble')
            ->orderBy('alias')
            ->get();
    }

    private function autorizarCuenta(CuentaServicio $cuenta): void
    {
        $hogarId = Auth::user()->persona?->hogar_id;
        $cuenta->loadMissing(['propiedad.persona']);
        abort_unless($cuenta->propiedad?->persona?->hogar_id === $hogarId, 403);
    }

    private function proveedoresData(): array
    {
        $proveedores = ProveedorServicio::where('activo', true)
            ->with('tipoServicio')
            ->orderBy('nombre_comercial')
            ->get();

        $proveedoresJson = $proveedores->map(function ($p) {
            return [
                'id'          => $p->id,
                'nombre'      => $p->nombre_comercial ?? '(sin nombre)',
                'tipo'        => $p->tipo_servicio_id,
                'tipo_nombre' => optional($p->tipoServicio)->nombre ?? '',
            ];
        })->values();

        return [$proveedores, $proveedoresJson];
    }

    public function index(Request $request): View
    {
        $hogarId     = Auth::user()->persona?->hogar_id;
        $propiedades = $this->propiedadesHogar();

        $propiedadId           = $request->get('propiedad');
        $propiedadSeleccionada = $propiedadId ? $propiedades->firstWhere('id', $propiedadId) : null;

        $cuentas = CuentaServicio::whereHas('propiedad.persona', fn ($q) => $q->where('hogar_id', $hogarId))
            ->with(['proveedor.tipoServicio', 'proveedor.empresa', 'propiedad.tipoInmueble'])
            ->when($propiedadSeleccionada, fn ($q) => $q->where('propiedad_id', $propiedadSeleccionada->id))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.cuentas-servicio-module.index', compact('propiedades', 'propiedadSeleccionada', 'cuentas'));
    }

    public function create(Request $request): View
    {
        $propiedades           = $this->propiedadesHogar();
        $propiedadId           = $request->get('propiedad');
        $propiedadSeleccionada = $propiedadId ? $propiedades->firstWhere('id', $propiedadId) : null;

        $tiposServicio = TipoServicio::where('activo', true)->orderBy('nombre')->get();
        [$proveedores, $proveedoresJson] = $this->proveedoresData();
        $hogarUsers = User::whereHas('persona', fn ($q) => $q->where('hogar_id', Auth::user()->persona?->hogar_id))
            ->with('persona')
            ->get();

        return view('dashboard.cuentas-servicio-module.create', compact(
            'propiedades', 'propiedadSeleccionada', 'tiposServicio', 'proveedores', 'hogarUsers', 'proveedoresJson'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $hogarId = Auth::user()->persona?->hogar_id;

        $request->validate([
            'propiedad_id'    => ['required', 'uuid', 'exists:propiedades_inmueble,id'],
            'proveedor_id'    => ['required', 'uuid', 'exists:proveedores_servicio,id'],
            'numero_cuenta'   => ['required', 'string', 'max:100'],
            'titular_user_id' => ['nullable', 'uuid', 'exists:users,id'],
            'estado'          => ['required', 'in:activa,suspendida,cancelada'],
            'fecha_inicio'    => ['nullable', 'date'],
            'notas'           => ['nullable', 'string', 'max:1000'],
        ]);

        $propiedad = PropiedadInmueble::with('persona')->findOrFail($request->propiedad_id);
        abort_unless($propiedad->persona?->hogar_id === $hogarId, 403);

        CuentaServicio::create([
            'propiedad_id'    => $propiedad->id,
            'proveedor_id'    => $request->proveedor_id,
            'numero_cuenta'   => $request->numero_cuenta,
            'titular_user_id' => $request->titular_user_id ?: null,
            'estado'          => $request->estado,
            'fecha_inicio'    => $request->fecha_inicio ?: null,
            'notas'           => $request->notas,
        ]);

        return redirect()->route('dashboard.cuentas-servicio.index', ['propiedad' => $propiedad->id])
            ->with('success', 'Cuenta de servicio registrada correctamente.');
    }

    public function show(CuentaServicio $cuentaServicio): View
    {
        $this->autorizarCuenta($cuentaServicio);
        $cuentaServicio->load(['proveedor.tipoServicio', 'proveedor.empresa', 'titular.persona', 'propiedad']);

        return view('dashboard.cuentas-servicio-module.show', compact('cuentaServicio'));
    }

    public function edit(CuentaServicio $cuentaServicio): View
    {
        $this->autorizarCuenta($cuentaServicio);

        $propiedades   = $this->propiedadesHogar();
        $tiposServicio = TipoServicio::where('activo', true)->orderBy('nombre')->get();
        [$proveedores, $proveedoresJson] = $this->proveedoresData();
        $hogarUsers = User::whereHas('persona', fn ($q) => $q->where('hogar_id', Auth::user()->persona?->hogar_id))
            ->with('persona')
            ->get();
        $cuenta = $cuentaServicio;

        return view('dashboard.cuentas-servicio-module.edit', compact(
            'cuenta', 'propiedades', 'tiposServicio', 'proveedores', 'hogarUsers', 'proveedoresJson'
        ));
    }

    public function update(UpdateCuentaServicioRequest $request, CuentaServicio $cuentaServicio): RedirectResponse
    {
        $this->autorizarCuenta($cuentaServicio);

        $cuentaServicio->update([
            'proveedor_id'    => $request->proveedor_id,
            'numero_cuenta'   => $request->numero_cuenta,
            'titular_user_id' => $request->titular_user_id ?: null,
            'estado'          => $request->estado,
            'fecha_inicio'    => $request->fecha_inicio ?: null,
            'notas'           => $request->notas,
        ]);

        return redirect()->route('dashboard.cuentas-servicio.index', ['propiedad' => $cuentaServicio->propiedad_id])
            ->with('success', 'Cuenta de servicio actualizada correctamente.');
    }

    public function destroy(CuentaServicio $cuentaServicio): RedirectResponse
    {
        $this->autorizarCuenta($cuentaServicio);

        $propiedadId = $cuentaServicio->propiedad_id;
        $cuentaServicio->delete();

        return redirect()->route('dashboard.cuentas-servicio.index', ['propiedad' => $propiedadId])
            ->with('success', 'Cuenta de servicio eliminada correctamente.');
    }
}

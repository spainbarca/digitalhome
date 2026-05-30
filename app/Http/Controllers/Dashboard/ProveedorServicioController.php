<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProveedorServicioRequest;
use App\Http\Requests\UpdateProveedorServicioRequest;
use App\Models\Empresa;
use App\Models\ProveedorServicio;
use App\Models\TipoServicio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProveedorServicioController extends Controller
{
    public function index(Request $request): View
    {
        $search   = $request->get('search', '');
        $tipoId   = $request->get('tipo_servicio_id', '');

        $proveedores = ProveedorServicio::with(['empresa', 'tipoServicio'])
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('nombre_comercial', 'like', "%{$search}%")
                  ->orWhereHas('empresa', fn ($e) => $e
                      ->where('razon_social', 'like', "%{$search}%")
                      ->orWhere('ruc', 'like', "%{$search}%")
                  );
            }))
            ->when($tipoId, fn ($q) => $q->where('tipo_servicio_id', $tipoId))
            ->orderBy('nombre_comercial')
            ->paginate(15)
            ->withQueryString();

        $tiposServicio = TipoServicio::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.proveedores-servicio.index', compact('proveedores', 'search', 'tipoId', 'tiposServicio'));
    }

    public function create(): View
    {
        $tiposServicio = TipoServicio::where('activo', true)->orderBy('nombre')->get();
        $empresas      = Empresa::orderBy('razon_social')->get();
        return view('dashboard.proveedores-servicio.create', compact('tiposServicio', 'empresas'));
    }

    public function store(StoreProveedorServicioRequest $request): RedirectResponse
    {
        $data           = $request->safe()->except(['logo']);
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('logo')) {
            $data['logo_url'] = $request->file('logo')->store('proveedores', 'public');
        }

        $proveedor = ProveedorServicio::create($data);

        return redirect()->route('dashboard.proveedores.show', $proveedor)
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function show(ProveedorServicio $proveedor): View
    {
        $proveedor->load(['empresa.sector', 'tipoServicio.unidadMedida', 'cuentasServicio.titular.persona']);
        return view('dashboard.proveedores-servicio.show', compact('proveedor'));
    }

    public function edit(ProveedorServicio $proveedor): View
    {
        $tiposServicio = TipoServicio::where('activo', true)->orderBy('nombre')->get();
        $empresas      = Empresa::orderBy('razon_social')->get();
        return view('dashboard.proveedores-servicio.edit', compact('proveedor', 'tiposServicio', 'empresas'));
    }

    public function update(UpdateProveedorServicioRequest $request, ProveedorServicio $proveedor): RedirectResponse
    {
        $data           = $request->safe()->except(['logo']);
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('logo')) {
            if ($proveedor->logo_url) {
                Storage::disk('public')->delete($proveedor->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('proveedores', 'public');
        }

        $proveedor->update($data);

        return redirect()->route('dashboard.proveedores.show', $proveedor)
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(ProveedorServicio $proveedor): RedirectResponse
    {
        $proveedor->delete();
        return redirect()->route('dashboard.proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}

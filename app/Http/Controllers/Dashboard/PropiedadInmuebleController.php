<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropiedadInmuebleRequest;
use App\Http\Requests\UpdatePropiedadInmuebleRequest;
use App\Models\Ciudad;
use App\Models\Pais;
use App\Models\PropiedadInmueble;
use App\Models\TipoInmueble;
use App\Models\UbigeoDepartamento;
use App\Models\UbigeoDistrito;
use App\Models\UbigeoProvincia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PropiedadInmuebleController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', PropiedadInmueble::class);

        $hogarId = Auth::user()->persona?->hogar_id;
        $q = $request->string('q')->trim();

        $propiedades = PropiedadInmueble::whereHas('persona', fn ($query) => $query->where('hogar_id', $hogarId))
            ->when($q, fn ($query) => $query->where(function ($query) use ($q) {
                $query->where('alias', 'like', "%{$q}%")
                      ->orWhere('direccion', 'like', "%{$q}%");
            }))
            ->with(['tipoInmueble'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.propiedades.index', compact('propiedades'));
    }

    public function create(): View
    {
        $this->authorize('create', PropiedadInmueble::class);

        $tiposInmueble   = TipoInmueble::where('activo', true)->orderBy('nombre')->get();
        $departamentos   = UbigeoDepartamento::orderBy('departamento')->get(['inei', 'departamento']);
        $provincias      = UbigeoProvincia::orderBy('provincia')->get(['inei', 'provincia', 'departamento_inei']);
        $distritos       = UbigeoDistrito::orderBy('distrito')->get(['inei', 'distrito', 'provincia_inei']);
        $paises          = Pais::orderBy('nombre')->get(['iso2', 'nombre']);
        $ciudades        = Ciudad::orderBy('nombre')->get(['id', 'nombre', 'pais_iso2']);

        return view('dashboard.propiedades.create', compact(
            'tiposInmueble', 'departamentos', 'provincias', 'distritos', 'paises', 'ciudades'
        ));
    }

    public function store(StorePropiedadInmuebleRequest $request): RedirectResponse
    {
        $this->authorize('create', PropiedadInmueble::class);

        $personaId = Auth::user()->persona->id;
        $data = $request->safe()->except(['avatar', 'ubicacion_tipo']);
        $data['persona_id'] = $personaId;

        if ($request->input('ubicacion_tipo') === 'peru') {
            $data['pais_iso2'] = null;
            $data['ciudad_id'] = null;
        } else {
            $data['distrito_inei'] = null;
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('propiedades', 'public');
            $data['avatar_url'] = Storage::url($path);
        }

        PropiedadInmueble::create($data);

        return redirect()->route('dashboard.propiedades.index')
            ->with('success', 'Propiedad creada correctamente.');
    }

    public function show(PropiedadInmueble $propiedad): View
    {
        $this->authorize('view', $propiedad);

        $propiedad->load(['tipoInmueble', 'persona', 'distrito', 'pais', 'ciudad']);

        return view('dashboard.propiedades.show', compact('propiedad'));
    }

    public function edit(PropiedadInmueble $propiedad): View
    {
        $this->authorize('update', $propiedad);

        $tiposInmueble   = TipoInmueble::where('activo', true)->orderBy('nombre')->get();
        $departamentos   = UbigeoDepartamento::orderBy('departamento')->get(['inei', 'departamento']);
        $provincias      = UbigeoProvincia::orderBy('provincia')->get(['inei', 'provincia', 'departamento_inei']);
        $distritos       = UbigeoDistrito::orderBy('distrito')->get(['inei', 'distrito', 'provincia_inei']);
        $paises          = Pais::orderBy('nombre')->get(['iso2', 'nombre']);
        $ciudades        = Ciudad::orderBy('nombre')->get(['id', 'nombre', 'pais_iso2']);

        $selectedDepartamentoInei = null;
        $selectedProvinciaInei    = null;

        if ($propiedad->distrito_inei) {
            $distrito = UbigeoDistrito::find($propiedad->distrito_inei);
            if ($distrito) {
                $selectedProvinciaInei    = $distrito->provincia_inei;
                $selectedDepartamentoInei = $distrito->departamento_inei;
            }
        }

        return view('dashboard.propiedades.edit', compact(
            'propiedad', 'tiposInmueble', 'departamentos', 'provincias', 'distritos',
            'paises', 'ciudades', 'selectedDepartamentoInei', 'selectedProvinciaInei'
        ));
    }

    public function update(UpdatePropiedadInmuebleRequest $request, PropiedadInmueble $propiedad): RedirectResponse
    {
        $this->authorize('update', $propiedad);

        $data = $request->safe()->except(['avatar', 'ubicacion_tipo']);

        if ($request->input('ubicacion_tipo') === 'peru') {
            $data['pais_iso2'] = null;
            $data['ciudad_id'] = null;
        } else {
            $data['distrito_inei'] = null;
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('propiedades', 'public');
            $data['avatar_url'] = Storage::url($path);
        }

        $propiedad->update($data);

        return redirect()->route('dashboard.propiedades.index')
            ->with('success', 'Propiedad actualizada correctamente.');
    }

    public function destroy(PropiedadInmueble $propiedad): RedirectResponse
    {
        $this->authorize('delete', $propiedad);

        $propiedad->delete();

        return redirect()->route('dashboard.propiedades.index')
            ->with('success', 'Propiedad eliminada correctamente.');
    }
}

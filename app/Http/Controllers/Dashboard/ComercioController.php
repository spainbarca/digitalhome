<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComercioRequest;
use App\Http\Requests\UpdateComercioRequest;
use App\Models\Comercio;
use App\Models\Empresa;
use App\Models\TipoComercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComercioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request)
    {
        $hogarId = $this->hogarId();

        $query = Comercio::with(['empresa.distrito', 'tipoComercio', 'distrito'])
            ->withCount('compras')
            ->where('hogar_id', $hogarId);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('nombre_referencial', 'like', "%{$q}%")
                   ->orWhereHas('empresa', fn($eq) => $eq->where('razon_social', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('tipo_id')) {
            $query->where('tipo_comercio_id', $request->tipo_id);
        }

        $comercios = $query->orderBy('nombre_referencial')->paginate(15)->withQueryString();
        $tipos     = TipoComercio::orderBy('nombre')->get();

        return view('dashboard.comercios.index', compact('comercios', 'tipos'));
    }

    public function create()
    {
        $tipos    = TipoComercio::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('razon_social')->get();

        return view('dashboard.comercios.create', compact('tipos', 'empresas'));
    }

    public function store(StoreComercioRequest $request)
    {
        $data = $request->validated();
        unset($data['pais_iso2'], $data['ciudad_id']);
        $data['hogar_id']  = $this->hogarId();
        $data['activo']    = $request->boolean('activo');
        $data['es_online'] = $request->boolean('es_online');

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('comercios/logos', 'public');
        }
        unset($data['logo']);

        if ($request->hasFile('imagen')) {
            $data['imagen_path'] = $request->file('imagen')->store('comercios', 'public');
        }
        unset($data['imagen']);

        if ($request->hasFile('banner')) {
            $data['banner_path'] = $request->file('banner')->store('comercios/banners', 'public');
        }
        unset($data['banner']);

        $comercio = Comercio::create($data);

        return redirect()->route('dashboard.comercios.show', $comercio)
            ->with('success', 'Comercio registrado correctamente.');
    }

    public function show(Comercio $comercio)
    {
        abort_unless($comercio->hogar_id === $this->hogarId(), 403);

        $comercio->load([
            'empresa.distrito',
            'tipoComercio',
            'distrito',
            'compras',
        ]);

        return view('dashboard.comercios.show', compact('comercio'));
    }

    public function edit(Comercio $comercio)
    {
        abort_unless($comercio->hogar_id === $this->hogarId(), 403);

        $comercio->load('empresa.distrito');

        $tipos    = TipoComercio::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('razon_social')->get();

        return view('dashboard.comercios.edit', compact('comercio', 'tipos', 'empresas'));
    }

    public function update(UpdateComercioRequest $request, Comercio $comercio)
    {
        abort_unless($comercio->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();
        unset($data['pais_iso2'], $data['ciudad_id']);
        $data['activo']    = $request->boolean('activo');
        $data['es_online'] = $request->boolean('es_online');

        if ($request->hasFile('logo')) {
            if ($comercio->logo_path) {
                Storage::disk('public')->delete($comercio->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('comercios/logos', 'public');
        }
        unset($data['logo']);

        if ($request->hasFile('imagen')) {
            if ($comercio->imagen_path) {
                Storage::disk('public')->delete($comercio->imagen_path);
            }
            $data['imagen_path'] = $request->file('imagen')->store('comercios', 'public');
        }
        unset($data['imagen']);

        if ($request->hasFile('banner')) {
            if ($comercio->banner_path) {
                Storage::disk('public')->delete($comercio->banner_path);
            }
            $data['banner_path'] = $request->file('banner')->store('comercios/banners', 'public');
        }
        unset($data['banner']);

        $comercio->update($data);

        return redirect()->route('dashboard.comercios.show', $comercio)
            ->with('success', 'Comercio actualizado correctamente.');
    }

    public function destroy(Comercio $comercio)
    {
        abort_unless($comercio->hogar_id === $this->hogarId(), 403);

        if ($comercio->logo_path) {
            Storage::disk('public')->delete($comercio->logo_path);
        }
        if ($comercio->imagen_path) {
            Storage::disk('public')->delete($comercio->imagen_path);
        }
        if ($comercio->banner_path) {
            Storage::disk('public')->delete($comercio->banner_path);
        }

        $comercio->delete();

        return redirect()->route('dashboard.comercios.index')
            ->with('success', 'Comercio eliminado correctamente.');
    }
}

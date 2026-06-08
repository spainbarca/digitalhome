<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntidadLegalRequest;
use App\Http\Requests\UpdateEntidadLegalRequest;
use App\Models\Empresa;
use App\Models\EntidadLegal;
use App\Models\TipoEntidadLegal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EntidadLegalController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');

        $entidades = EntidadLegal::with(['tipoEntidadLegal', 'empresa'])
            ->when($search, fn ($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.entidades-legales.index', compact('entidades', 'search'));
    }

    public function create(): View
    {
        $empresas = Empresa::orderBy('razon_social')->get();
        $tipos    = TipoEntidadLegal::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.entidades-legales.create', compact('empresas', 'tipos'));
    }

    public function store(StoreEntidadLegalRequest $request): RedirectResponse
    {
        $data           = $request->safe()->except(['logo', 'imagen', 'banner']);
        $data['activo'] = $request->boolean('activo');

        foreach (['logo' => 'logo_path', 'imagen' => 'imagen_path', 'banner' => 'banner_path'] as $field => $column) {
            if ($request->hasFile($field)) {
                $data[$column] = $request->file($field)->store('entidades-legales', 'public');
            }
        }

        $entidad = EntidadLegal::create($data);

        return redirect()->route('dashboard.entidades-legales.show', $entidad)
            ->with('success', 'Entidad legal registrada correctamente.');
    }

    public function show(EntidadLegal $entidadLegal): View
    {
        $entidadLegal->load([
            'tipoEntidadLegal',
            'empresa',
            'distrito',
            'documentosLegales.tipoDocumentoLegal',
            'documentosLegales.estadoDocumentoLegal',
        ]);
        return view('dashboard.entidades-legales.show', compact('entidadLegal'));
    }

    public function edit(EntidadLegal $entidadLegal): View
    {
        $empresas = Empresa::orderBy('razon_social')->get();
        $tipos    = TipoEntidadLegal::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.entidades-legales.edit', compact('entidadLegal', 'empresas', 'tipos'));
    }

    public function update(UpdateEntidadLegalRequest $request, EntidadLegal $entidadLegal): RedirectResponse
    {
        $data           = $request->safe()->except(['logo', 'imagen', 'banner']);
        $data['activo'] = $request->boolean('activo');

        foreach (['logo' => 'logo_path', 'imagen' => 'imagen_path', 'banner' => 'banner_path'] as $field => $column) {
            if ($request->hasFile($field)) {
                if ($entidadLegal->$column) {
                    Storage::disk('public')->delete($entidadLegal->$column);
                }
                $data[$column] = $request->file($field)->store('entidades-legales', 'public');
            }
        }

        $entidadLegal->update($data);

        return redirect()->route('dashboard.entidades-legales.show', $entidadLegal)
            ->with('success', 'Entidad legal actualizada correctamente.');
    }

    public function destroy(EntidadLegal $entidadLegal): RedirectResponse
    {
        $entidadLegal->delete();
        return redirect()->route('dashboard.entidades-legales.index')
            ->with('success', 'Entidad legal eliminada correctamente.');
    }
}

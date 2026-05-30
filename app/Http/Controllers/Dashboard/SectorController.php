<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;
use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');

        try {
            $sectores = Sector::withCount('empresas')
                ->when($search, fn ($q) => $q->where('nombre', 'like', "%{$search}%"))
                ->orderBy('nombre')
                ->paginate(20)
                ->withQueryString();
        } catch (\Exception $e) {
            $sectores = Sector::when($search, fn ($q) => $q->where('nombre', 'like', "%{$search}%"))
                ->orderBy('nombre')
                ->paginate(20)
                ->withQueryString();
            $sectores->each(fn ($s) => $s->empresas_count = 0);
        }

        return view('dashboard.sectores.index', compact('sectores', 'search'));
    }

    public function create(): View
    {
        $this->authorize('create', Sector::class);
        return view('dashboard.sectores.create');
    }

    public function store(StoreSectorRequest $request): RedirectResponse
    {
        $this->authorize('create', Sector::class);

        $data           = $request->validated();
        $data['activo'] = $request->boolean('activo');

        $sector = Sector::create($data);

        return redirect()->route('dashboard.sectores.show', $sector)
            ->with('success', 'Sector registrado correctamente.');
    }

    public function show(Sector $sector): View
    {
        $this->authorize('view', $sector);
        try {
            $sector->loadCount('empresas');
        } catch (\Exception $e) {
            $sector->empresas_count = 0;
        }
        return view('dashboard.sectores.show', compact('sector'));
    }

    public function edit(Sector $sector): View
    {
        $this->authorize('update', $sector);
        return view('dashboard.sectores.edit', compact('sector'));
    }

    public function update(UpdateSectorRequest $request, Sector $sector): RedirectResponse
    {
        $this->authorize('update', $sector);

        $data           = $request->validated();
        $data['activo'] = $request->boolean('activo');

        $sector->update($data);

        return redirect()->route('dashboard.sectores.show', $sector)
            ->with('success', 'Sector actualizado correctamente.');
    }

    public function destroy(Sector $sector): RedirectResponse
    {
        $this->authorize('delete', $sector);

        if ($sector->empresas()->exists()) {
            return back()->with('error', 'No se puede eliminar el sector porque tiene empresas asociadas.');
        }

        $sector->delete();

        return redirect()->route('dashboard.sectores.index')
            ->with('success', 'Sector eliminado correctamente.');
    }
}

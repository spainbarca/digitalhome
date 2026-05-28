<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\Persona;
use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonaController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Persona::class);

        $hogarId = auth()->user()->persona?->hogar_id;
        $q = $request->string('q')->trim();

        $personas = Persona::where('hogar_id', $hogarId)
            ->when($q, fn ($query) => $query->where(function ($query) use ($q) {
                $query->where('nombres', 'like', "%{$q}%")
                      ->orWhere('apellido_paterno', 'like', "%{$q}%")
                      ->orWhere('apellido_materno', 'like', "%{$q}%");
            }))
            ->with('tipoDocumento')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.personas.index', compact('personas'));
    }

    public function create(): View
    {
        $this->authorize('create', Persona::class);

        $tiposDocumento = TipoDocumento::all();

        return view('dashboard.personas.create', compact('tiposDocumento'));
    }

    public function store(StorePersonaRequest $request): RedirectResponse
    {
        $this->authorize('create', Persona::class);

        $hogarId = auth()->user()->persona?->hogar_id;

        Persona::create(array_merge($request->validated(), ['hogar_id' => $hogarId]));

        return redirect()->route('dashboard.personas.index')
            ->with('success', 'Persona creada correctamente.');
    }

    public function show(Persona $persona): View
    {
        $this->authorize('view', $persona);

        $persona->load('tipoDocumento');

        return view('dashboard.personas.show', compact('persona'));
    }

    public function edit(Persona $persona): View
    {
        $this->authorize('update', $persona);

        $tiposDocumento = TipoDocumento::all();

        return view('dashboard.personas.edit', compact('persona', 'tiposDocumento'));
    }

    public function update(UpdatePersonaRequest $request, Persona $persona): RedirectResponse
    {
        $this->authorize('update', $persona);

        $persona->update($request->validated());

        return redirect()->route('dashboard.personas.index')
            ->with('success', 'Persona actualizada correctamente.');
    }

    public function destroy(Persona $persona): RedirectResponse
    {
        $this->authorize('delete', $persona);

        $persona->delete();

        return redirect()->route('dashboard.personas.index')
            ->with('success', 'Persona eliminada correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHogarRequest;
use App\Http\Requests\UpdateHogarRequest;
use App\Models\Hogar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HogarController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Hogar::class);

        $hogar = Hogar::where('owner_id', auth()->id())
            ->with('owner')
            ->first();

        return view('dashboard.hogares.index', compact('hogar'));
    }

    public function create(): View
    {
        $this->authorize('create', Hogar::class);

        return view('dashboard.hogares.create');
    }

    public function store(StoreHogarRequest $request): RedirectResponse
    {
        $this->authorize('create', Hogar::class);

        $data = array_merge($request->validated(), ['owner_id' => auth()->id()]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('hogares', 'public');
            $data['avatar_url'] = Storage::url($path);
        }

        $hogar = Hogar::create($data);

        return redirect()->route('dashboard.hogares.show', $hogar)
            ->with('success', 'Hogar creado correctamente.');
    }

    public function show(Hogar $hogar): View
    {
        $this->authorize('view', $hogar);

        $hogar->load(['owner', 'miembros']);

        $totalMiembros  = $hogar->miembros->count();
        $miembrosActivos = $hogar->miembros->where('estado', 'activo')->count();

        return view('dashboard.hogares.show', compact('hogar', 'totalMiembros', 'miembrosActivos'));
    }

    public function edit(Hogar $hogar): View
    {
        $this->authorize('update', $hogar);

        return view('dashboard.hogares.edit', compact('hogar'));
    }

    public function update(UpdateHogarRequest $request, Hogar $hogar): RedirectResponse
    {
        $this->authorize('update', $hogar);

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('hogares', 'public');
            $data['avatar_url'] = Storage::url($path);
        }

        $hogar->update($data);

        return redirect()->route('dashboard.hogares.show', $hogar)
            ->with('success', 'Hogar actualizado correctamente.');
    }

    public function destroy(Hogar $hogar): RedirectResponse
    {
        $this->authorize('delete', $hogar);

        $hogar->delete();

        return redirect()->route('dashboard.hogares.index')
            ->with('success', 'Hogar eliminado correctamente.');
    }
}

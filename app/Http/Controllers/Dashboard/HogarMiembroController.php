<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHogarMiembroRequest;
use App\Http\Requests\UpdateHogarMiembroRequest;
use App\Models\Hogar;
use App\Models\HogarMiembro;
use App\Models\Parentesco;
use App\Models\Persona;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HogarMiembroController extends Controller
{
    public function index(Hogar $hogar): View
    {
        $this->authorize('viewAny', HogarMiembro::class);

        $search = request('search');

        $miembros = HogarMiembro::where('hogar_id', $hogar->id)
            ->with(['user.persona.tipoDocumento', 'parentesco'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user.persona', function ($q2) use ($search) {
                    $q2->where('nombres', 'like', "%$search%")
                       ->orWhere('apellido_paterno', 'like', "%$search%")
                       ->orWhere('apellido_materno', 'like', "%$search%");
                });
            })
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.hogar-miembros.index', compact('hogar', 'miembros'));
    }

    public function create(Hogar $hogar): View
    {
        $this->authorize('create', [HogarMiembro::class, $hogar]);

        $hogarId = auth()->user()->persona?->hogar_id;

        $personas = Persona::where(function ($q) use ($hogarId) {
            $q->whereNull('hogar_id')
              ->orWhere('hogar_id', $hogarId);
        })
        ->whereNotIn('id', function ($sub) use ($hogar) {
            $sub->select('users.persona_id')
                ->from('users')
                ->join('hogar_miembros', 'hogar_miembros.user_id', '=', 'users.id')
                ->where('hogar_miembros.hogar_id', $hogar->id)
                ->whereNotNull('users.persona_id');
        })
        ->get();

        $parentescos = Parentesco::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.hogar-miembros.create', compact('hogar', 'personas', 'parentescos'));
    }

    public function store(StoreHogarMiembroRequest $request, Hogar $hogar): RedirectResponse
    {
        $this->authorize('create', [HogarMiembro::class, $hogar]);

        $persona = Persona::findOrFail($request->persona_id);

        abort_unless(
            $persona->user !== null,
            422,
            'La persona seleccionada no tiene cuenta de usuario asociada.'
        );

        HogarMiembro::create([
            'hogar_id'      => $hogar->id,
            'user_id'       => $persona->user->id,
            'parentesco_id' => $request->parentesco_id,
            'rol'           => $request->boolean('es_titular') ? 'admin' : 'viewer',
            'apodo'         => $request->apodo,
        ]);

        return redirect()->route('dashboard.hogares.miembros.index', $hogar)
            ->with('success', 'Miembro agregado correctamente.');
    }

    public function edit(Hogar $hogar, HogarMiembro $miembro): View
    {
        $this->authorize('update', $miembro);

        $personas = Persona::where('hogar_id', $hogar->id)->get();
        $parentescos = Parentesco::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.hogar-miembros.edit', compact('hogar', 'miembro', 'personas', 'parentescos'));
    }

    public function update(UpdateHogarMiembroRequest $request, Hogar $hogar, HogarMiembro $miembro): RedirectResponse
    {
        $this->authorize('update', $miembro);

        $miembro->update([
            'parentesco_id' => $request->parentesco_id,
            'rol'           => $request->boolean('es_titular') ? 'admin' : 'viewer',
            'apodo'         => $request->apodo,
        ]);

        return redirect()->route('dashboard.hogares.miembros.index', $hogar)
            ->with('success', 'Miembro actualizado correctamente.');
    }

    public function destroy(Hogar $hogar, HogarMiembro $miembro): RedirectResponse
    {
        $this->authorize('delete', $miembro);

        $miembro->delete();

        return redirect()->route('dashboard.hogares.miembros.index', $hogar)
            ->with('success', 'Miembro eliminado correctamente.');
    }
}

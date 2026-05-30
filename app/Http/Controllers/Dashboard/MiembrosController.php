<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Hogar;
use App\Models\HogarMiembro;
use App\Models\Parentesco;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MiembrosController extends Controller
{
    private function hogaresUsuario(): Collection
    {
        $userId  = Auth::id();
        $propios = Hogar::where('owner_id', $userId)->get();
        $ajenos  = Hogar::whereHas('miembros', fn ($q) => $q->where('user_id', $userId))->get();
        return $propios->merge($ajenos)->unique('id')->sortBy('nombre')->values();
    }

    private function autorizarMiembro(HogarMiembro $miembro): void
    {
        abort_unless($this->hogaresUsuario()->pluck('id')->contains($miembro->hogar_id), 403);
    }

    public function index(Request $request): View
    {
        $hogares = $this->hogaresUsuario();
        $hogarId = $request->get('hogar');
        $search  = $request->get('search', '');

        $hogarSeleccionado = $hogarId
            ? $hogares->firstWhere('id', $hogarId)
            : ($hogares->count() === 1 ? $hogares->first() : null);

        $miembros = HogarMiembro::whereIn('hogar_id', $hogares->pluck('id'))
            ->with(['user.persona.tipoDocumento', 'parentesco', 'hogar'])
            ->when($hogarSeleccionado, fn ($q) => $q->where('hogar_id', $hogarSeleccionado->id))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user.persona', fn ($q2) => $q2
                    ->where('nombres', 'like', "%$search%")
                    ->orWhere('apellido_paterno', 'like', "%$search%")
                    ->orWhere('apellido_materno', 'like', "%$search%"));
            })
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.miembros-module.index', compact(
            'hogares', 'hogarSeleccionado', 'miembros', 'search'
        ));
    }

    public function create(Request $request): View
    {
        $hogares = $this->hogaresUsuario();
        $hogarId = $request->get('hogar', old('hogar_id'));
        $hogarSeleccionado = $hogarId ? $hogares->firstWhere('id', $hogarId) : ($hogares->count() === 1 ? $hogares->first() : null);

        $parentescos = Parentesco::where('activo', true)->orderBy('nombre')->get();
        $personas    = collect();

        if ($hogarSeleccionado) {
            $hogarActualId = Auth::user()->persona?->hogar_id;
            $personas = Persona::where(function ($q) use ($hogarActualId) {
                    $q->whereNull('hogar_id')->orWhere('hogar_id', $hogarActualId);
                })
                ->whereNotIn('id', function ($sub) use ($hogarSeleccionado) {
                    $sub->select('users.persona_id')
                        ->from('users')
                        ->join('hogar_miembros', 'hogar_miembros.user_id', '=', 'users.id')
                        ->where('hogar_miembros.hogar_id', $hogarSeleccionado->id)
                        ->whereNotNull('users.persona_id');
                })
                ->orderBy('nombres')
                ->get();
        }

        return view('dashboard.miembros-module.create', compact(
            'hogares', 'hogarSeleccionado', 'parentescos', 'personas'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'hogar_id'      => ['required', 'uuid', 'exists:hogares,id'],
            'persona_id'    => ['required', 'uuid', 'exists:personas,id'],
            'parentesco_id' => ['required', 'uuid', 'exists:parentescos,id'],
            'apodo'         => ['nullable', 'string', 'max:60'],
            'es_titular'    => ['nullable'],
        ]);

        $hogares = $this->hogaresUsuario();
        $hogar   = $hogares->firstWhere('id', $request->hogar_id);
        abort_unless($hogar !== null, 403);

        $persona = Persona::with('user')->findOrFail($request->persona_id);
        abort_unless($persona->user !== null, 422, 'La persona seleccionada no tiene cuenta de usuario asociada.');

        HogarMiembro::create([
            'hogar_id'      => $hogar->id,
            'user_id'       => $persona->user->id,
            'parentesco_id' => $request->parentesco_id,
            'rol'           => $request->boolean('es_titular') ? 'admin' : 'viewer',
            'apodo'         => $request->apodo,
        ]);

        return redirect()->route('dashboard.miembros.index', ['hogar' => $hogar->id])
            ->with('success', 'Miembro agregado correctamente.');
    }

    public function edit(HogarMiembro $miembro): View
    {
        $this->autorizarMiembro($miembro);
        $miembro->load(['hogar', 'user.persona', 'parentesco']);
        $parentescos = Parentesco::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.miembros-module.edit', compact('miembro', 'parentescos'));
    }

    public function update(Request $request, HogarMiembro $miembro): RedirectResponse
    {
        $this->autorizarMiembro($miembro);
        $request->validate([
            'parentesco_id' => ['required', 'uuid', 'exists:parentescos,id'],
            'apodo'         => ['nullable', 'string', 'max:60'],
            'es_titular'    => ['nullable'],
        ]);

        $miembro->update([
            'parentesco_id' => $request->parentesco_id,
            'rol'           => $request->boolean('es_titular') ? 'admin' : 'viewer',
            'apodo'         => $request->apodo,
        ]);

        return redirect()->route('dashboard.miembros.index', ['hogar' => $miembro->hogar_id])
            ->with('success', 'Miembro actualizado correctamente.');
    }

    public function destroy(HogarMiembro $miembro): RedirectResponse
    {
        $this->autorizarMiembro($miembro);
        $hogarId = $miembro->hogar_id;
        $miembro->delete();
        return redirect()->route('dashboard.miembros.index', ['hogar' => $hogarId])
            ->with('success', 'Miembro eliminado del hogar correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpleadorRequest;
use App\Http\Requests\UpdateEmpleadorRequest;
use App\Models\Empleador;
use App\Models\Empresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmpleadorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');

        $empleadores = Empleador::with(['empresa', 'distrito'])
            ->withCount('empleos')
            ->when($search, fn ($q) => $q->where('nombre', 'like', "%{$search}%")
                ->orWhereHas('empresa', fn ($sq) => $sq->where('razon_social', 'like', "%{$search}%"))
            )
            ->orderBy('nombre')
            ->paginate(16)
            ->withQueryString();

        return view('dashboard.empleadores.index', compact('empleadores', 'search'));
    }

    public function create(): View
    {
        $empresas = Empresa::where('activo', true)->orderBy('razon_social')->get();
        return view('dashboard.empleadores.create', compact('empresas'));
    }

    public function store(StoreEmpleadorRequest $request): RedirectResponse
    {
        $data           = $request->safe()->except(['logo_path', 'imagen_path', 'banner_path']);
        $data['activo'] = $request->boolean('activo');

        foreach (['logo_path', 'imagen_path', 'banner_path'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('empleadores', 'public');
            }
        }

        $empleador = Empleador::create($data);

        return redirect()->route('dashboard.empleadores.show', $empleador)
            ->with('success', 'Empleador registrado correctamente.');
    }

    public function show(Empleador $empleador): View
    {
        $empleador->load([
            'empresa.distrito',
            'distrito',
            'empleos.hogarMiembro.user.persona',
            'empleos.estadoEmpleo',
            'empleos.modalidadLaboral',
        ]);

        return view('dashboard.empleadores.show', compact('empleador'));
    }

    public function edit(Empleador $empleador): View
    {
        $empleador->load(['empresa', 'distrito']);
        $empresas = Empresa::where('activo', true)->orderBy('razon_social')->get();
        return view('dashboard.empleadores.edit', compact('empleador', 'empresas'));
    }

    public function update(UpdateEmpleadorRequest $request, Empleador $empleador): RedirectResponse
    {
        $data           = $request->safe()->except(['logo_path', 'imagen_path', 'banner_path']);
        $data['activo'] = $request->boolean('activo');

        foreach (['logo_path', 'imagen_path', 'banner_path'] as $field) {
            if ($request->hasFile($field)) {
                if ($empleador->$field) {
                    Storage::disk('public')->delete($empleador->$field);
                }
                $data[$field] = $request->file($field)->store('empleadores', 'public');
            }
        }

        $empleador->update($data);

        return redirect()->route('dashboard.empleadores.show', $empleador)
            ->with('success', 'Empleador actualizado correctamente.');
    }

    public function destroy(Empleador $empleador): RedirectResponse
    {
        $empleador->delete();

        return redirect()->route('dashboard.empleadores.index')
            ->with('success', 'Empleador eliminado correctamente.');
    }
}

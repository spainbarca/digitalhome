<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOperadorViajeRequest;
use App\Http\Requests\UpdateOperadorViajeRequest;
use App\Models\Empresa;
use App\Models\OperadorViaje;
use App\Models\TipoOperadorViaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OperadorViajeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $tipoId = $request->get('tipo_id', '');

        $tipos = TipoOperadorViaje::orderBy('nombre')->get();

        $operadores = OperadorViaje::with(['empresa', 'tipoOperadorViaje'])
            ->withCount('reservas')
            ->when($search, fn($q) => $q->whereHas('empresa', fn($sq) =>
                $sq->where('razon_social', 'like', "%{$search}%")
                   ->orWhere('ruc', 'like', "%{$search}%")
            ))
            ->when($tipoId, fn($q) => $q->where('tipo_operador_viaje_id', $tipoId))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.operadores-viaje.index', compact('operadores', 'tipos', 'search', 'tipoId'));
    }

    public function create(): View
    {
        $empresas = Empresa::where('activo', true)->orderBy('razon_social')->get();
        $tipos    = TipoOperadorViaje::orderBy('nombre')->get();
        return view('dashboard.operadores-viaje.create', compact('empresas', 'tipos'));
    }

    public function store(StoreOperadorViajeRequest $request): RedirectResponse
    {
        $data = collect($request->validated())
            ->except(['logo_path', 'banner_path'])
            ->toArray();

        $operador = OperadorViaje::create($data);

        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $path = $file->storeAs("operadores_viaje/{$operador->id}", 'logo.' . $file->getClientOriginalExtension(), 'public');
            $operador->update(['logo_path' => $path]);
        }

        if ($request->hasFile('banner_path')) {
            $file = $request->file('banner_path');
            $path = $file->storeAs("operadores_viaje/{$operador->id}", 'banner.' . $file->getClientOriginalExtension(), 'public');
            $operador->update(['banner_path' => $path]);
        }

        return redirect()->route('dashboard.operadores-viaje.show', $operador)
            ->with('success', 'Operador de viaje registrado correctamente.');
    }

    public function show(OperadorViaje $operadoresViaje): View
    {
        $operadoresViaje->load(['empresa', 'tipoOperadorViaje']);
        return view('dashboard.operadores-viaje.show', compact('operadoresViaje'));
    }

    public function edit(OperadorViaje $operadoresViaje): View
    {
        $operadoresViaje->load('empresa');
        $empresas = Empresa::where('activo', true)->orderBy('razon_social')->get();
        $tipos    = TipoOperadorViaje::orderBy('nombre')->get();
        return view('dashboard.operadores-viaje.edit', compact('operadoresViaje', 'empresas', 'tipos'));
    }

    public function update(UpdateOperadorViajeRequest $request, OperadorViaje $operadoresViaje): RedirectResponse
    {
        $data = collect($request->validated())
            ->except(['logo_path', 'banner_path'])
            ->toArray();

        $operadoresViaje->update($data);

        if ($request->hasFile('logo_path')) {
            if ($operadoresViaje->logo_path) {
                Storage::disk('public')->delete($operadoresViaje->logo_path);
            }
            $file = $request->file('logo_path');
            $path = $file->storeAs("operadores_viaje/{$operadoresViaje->id}", 'logo.' . $file->getClientOriginalExtension(), 'public');
            $operadoresViaje->update(['logo_path' => $path]);
        }

        if ($request->hasFile('banner_path')) {
            if ($operadoresViaje->banner_path) {
                Storage::disk('public')->delete($operadoresViaje->banner_path);
            }
            $file = $request->file('banner_path');
            $path = $file->storeAs("operadores_viaje/{$operadoresViaje->id}", 'banner.' . $file->getClientOriginalExtension(), 'public');
            $operadoresViaje->update(['banner_path' => $path]);
        }

        return redirect()->route('dashboard.operadores-viaje.show', $operadoresViaje)
            ->with('success', 'Operador de viaje actualizado correctamente.');
    }

    public function destroy(OperadorViaje $operadoresViaje): RedirectResponse
    {
        $operadoresViaje->delete();

        return redirect()->route('dashboard.operadores-viaje.index')
            ->with('success', 'Operador de viaje eliminado correctamente.');
    }
}

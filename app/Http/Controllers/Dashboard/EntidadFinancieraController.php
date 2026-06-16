<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntidadFinancieraRequest;
use App\Http\Requests\UpdateEntidadFinancieraRequest;
use App\Models\Empresa;
use App\Models\EntidadFinanciera;
use App\Models\TipoEntidadFinanciera;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EntidadFinancieraController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $tipoId = $request->get('tipo_id', '');

        $tipos = TipoEntidadFinanciera::orderBy('nombre')->get();

        $entidades = EntidadFinanciera::with(['empresa', 'tipoEntidadFinanciera'])
            ->withCount('productosFinancieros')
            ->when($search, fn($q) => $q->whereHas('empresa', fn($sq) =>
                $sq->where('razon_social', 'like', "%{$search}%")
                   ->orWhere('ruc', 'like', "%{$search}%")
            ))
            ->when($tipoId, fn($q) => $q->where('tipo_entidad_financiera_id', $tipoId))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.entidades-financieras.index', compact('entidades', 'tipos', 'search', 'tipoId'));
    }

    public function create(): View
    {
        $empresas = Empresa::where('activo', true)->orderBy('razon_social')->get();
        $tipos    = TipoEntidadFinanciera::orderBy('nombre')->get();
        return view('dashboard.entidades-financieras.create', compact('empresas', 'tipos'));
    }

    public function store(StoreEntidadFinancieraRequest $request): RedirectResponse
    {
        $data = collect($request->validated())
            ->except(['logo_path', 'banner_path'])
            ->toArray();

        $entidad = EntidadFinanciera::create($data);

        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $path = $file->storeAs("entidades_financieras/{$entidad->id}", 'logo.' . $file->getClientOriginalExtension(), 'public');
            $entidad->update(['logo_path' => $path]);
        }

        if ($request->hasFile('banner_path')) {
            $file = $request->file('banner_path');
            $path = $file->storeAs("entidades_financieras/{$entidad->id}", 'banner.' . $file->getClientOriginalExtension(), 'public');
            $entidad->update(['banner_path' => $path]);
        }

        return redirect()->route('dashboard.entidades-financieras.show', $entidad)
            ->with('success', 'Entidad financiera registrada correctamente.');
    }

    public function show(EntidadFinanciera $entidadFinanciera): View
    {
        $entidadFinanciera->load(['empresa', 'tipoEntidadFinanciera', 'productosFinancieros']);
        return view('dashboard.entidades-financieras.show', compact('entidadFinanciera'));
    }

    public function edit(EntidadFinanciera $entidadFinanciera): View
    {
        $entidadFinanciera->load('empresa');
        $empresas = Empresa::where('activo', true)->orderBy('razon_social')->get();
        $tipos    = TipoEntidadFinanciera::orderBy('nombre')->get();
        return view('dashboard.entidades-financieras.edit', compact('entidadFinanciera', 'empresas', 'tipos'));
    }

    public function update(UpdateEntidadFinancieraRequest $request, EntidadFinanciera $entidadFinanciera): RedirectResponse
    {
        $data = collect($request->validated())
            ->except(['logo_path', 'banner_path'])
            ->toArray();

        $entidadFinanciera->update($data);

        if ($request->hasFile('logo_path')) {
            if ($entidadFinanciera->logo_path) {
                Storage::disk('public')->delete($entidadFinanciera->logo_path);
            }
            $file = $request->file('logo_path');
            $path = $file->storeAs("entidades_financieras/{$entidadFinanciera->id}", 'logo.' . $file->getClientOriginalExtension(), 'public');
            $entidadFinanciera->update(['logo_path' => $path]);
        }

        if ($request->hasFile('banner_path')) {
            if ($entidadFinanciera->banner_path) {
                Storage::disk('public')->delete($entidadFinanciera->banner_path);
            }
            $file = $request->file('banner_path');
            $path = $file->storeAs("entidades_financieras/{$entidadFinanciera->id}", 'banner.' . $file->getClientOriginalExtension(), 'public');
            $entidadFinanciera->update(['banner_path' => $path]);
        }

        return redirect()->route('dashboard.entidades-financieras.show', $entidadFinanciera)
            ->with('success', 'Entidad financiera actualizada correctamente.');
    }

    public function destroy(EntidadFinanciera $entidadFinanciera): RedirectResponse
    {
        $entidadFinanciera->delete();

        return redirect()->route('dashboard.entidades-financieras.index')
            ->with('success', 'Entidad financiera eliminada correctamente.');
    }
}

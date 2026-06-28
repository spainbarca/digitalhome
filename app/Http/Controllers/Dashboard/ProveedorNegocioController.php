<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProveedorNegocioRequest;
use App\Http\Requests\UpdateProveedorNegocioRequest;
use App\Models\Empresa;
use App\Models\ProveedorNegocio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProveedorNegocioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request): View
    {
        $hogarId = $this->hogarId();
        $search  = $request->get('search', '');

        $proveedores = ProveedorNegocio::where('hogar_id', $hogarId)
            ->with('empresa')
            ->when($search, fn($q) => $q->where(function ($sq) use ($search) {
                $sq->where('nombre', 'like', "%{$search}%")
                   ->orWhere('sigla', 'like', "%{$search}%")
                   ->orWhereHas('empresa', fn($eq) =>
                       $eq->where('razon_social', 'like', "%{$search}%")
                          ->orWhere('ruc', 'like', "%{$search}%")
                   );
            }))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.proveedores-negocio.index', compact('proveedores', 'search'));
    }

    public function create(): View
    {
        $empresas = Empresa::where('activo', true)->with('sector')->orderBy('razon_social')->get();
        return view('dashboard.proveedores-negocio.create', compact('empresas'));
    }

    public function store(StoreProveedorNegocioRequest $request): RedirectResponse
    {
        $data             = collect($request->validated())->except(['logo_path', 'banner_path'])->toArray();
        $data['hogar_id'] = $this->hogarId();

        $proveedor = ProveedorNegocio::create($data);

        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $path = $file->storeAs("proveedores_negocio/{$proveedor->id}", 'logo.' . $file->getClientOriginalExtension(), 'public');
            $proveedor->update(['logo_path' => $path]);
        }

        if ($request->hasFile('banner_path')) {
            $file = $request->file('banner_path');
            $path = $file->storeAs("proveedores_negocio/{$proveedor->id}", 'banner.' . $file->getClientOriginalExtension(), 'public');
            $proveedor->update(['banner_path' => $path]);
        }

        return redirect()->route('dashboard.proveedores-negocio.show', $proveedor)
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function show(ProveedorNegocio $proveedoresNegocio): View
    {
        abort_unless($proveedoresNegocio->hogar_id === $this->hogarId(), 403);
        $proveedoresNegocio->load('empresa.sector');
        return view('dashboard.proveedores-negocio.show', compact('proveedoresNegocio'));
    }

    public function edit(ProveedorNegocio $proveedoresNegocio): View
    {
        abort_unless($proveedoresNegocio->hogar_id === $this->hogarId(), 403);
        $proveedoresNegocio->load('empresa');
        $empresas = Empresa::where('activo', true)->with('sector')->orderBy('razon_social')->get();
        return view('dashboard.proveedores-negocio.edit', compact('proveedoresNegocio', 'empresas'));
    }

    public function update(UpdateProveedorNegocioRequest $request, ProveedorNegocio $proveedoresNegocio): RedirectResponse
    {
        abort_unless($proveedoresNegocio->hogar_id === $this->hogarId(), 403);

        $data = collect($request->validated())->except(['logo_path', 'banner_path'])->toArray();
        $proveedoresNegocio->update($data);

        if ($request->hasFile('logo_path')) {
            if ($proveedoresNegocio->logo_path) {
                Storage::disk('public')->delete($proveedoresNegocio->logo_path);
            }
            $file = $request->file('logo_path');
            $path = $file->storeAs("proveedores_negocio/{$proveedoresNegocio->id}", 'logo.' . $file->getClientOriginalExtension(), 'public');
            $proveedoresNegocio->update(['logo_path' => $path]);
        }

        if ($request->hasFile('banner_path')) {
            if ($proveedoresNegocio->banner_path) {
                Storage::disk('public')->delete($proveedoresNegocio->banner_path);
            }
            $file = $request->file('banner_path');
            $path = $file->storeAs("proveedores_negocio/{$proveedoresNegocio->id}", 'banner.' . $file->getClientOriginalExtension(), 'public');
            $proveedoresNegocio->update(['banner_path' => $path]);
        }

        return redirect()->route('dashboard.proveedores-negocio.show', $proveedoresNegocio)
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(ProveedorNegocio $proveedoresNegocio): RedirectResponse
    {
        abort_unless($proveedoresNegocio->hogar_id === $this->hogarId(), 403);
        $proveedoresNegocio->delete();
        return redirect()->route('dashboard.proveedores-negocio.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}

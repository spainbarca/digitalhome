<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstitucionEducativaRequest;
use App\Http\Requests\UpdateInstitucionEducativaRequest;
use App\Models\InstitucionEducativa;
use App\Models\TipoInstitucionEducativa;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstitucionEducativaController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request)
    {
        $hogarId = $this->hogarId();

        $query = InstitucionEducativa::with(['empresa', 'tipoInstitucionEducativa'])
            ->withCount('matriculas')
            ->where('hogar_id', $hogarId);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('nombre_referencial', 'like', "%{$q}%")
                   ->orWhereHas('empresa', fn($eq) => $eq->where('razon_social', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('tipo_id')) {
            $query->where('tipo_institucion_educativa_id', $request->tipo_id);
        }

        $instituciones = $query->orderBy('nombre_referencial')->paginate(15)->withQueryString();
        $tipos = TipoInstitucionEducativa::orderBy('nombre')->get();

        return view('dashboard.instituciones-educativas.index', compact('instituciones', 'tipos'));
    }

    public function create()
    {
        $tipos    = TipoInstitucionEducativa::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('razon_social')->get();

        return view('dashboard.instituciones-educativas.create', compact('tipos', 'empresas'));
    }

    public function store(StoreInstitucionEducativaRequest $request)
    {
        $data = $request->validated();
        $data['hogar_id'] = $this->hogarId();
        $data['activo']   = $request->boolean('activo');

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('instituciones-educativas/logos', 'public');
        }
        unset($data['logo']);

        if ($request->hasFile('imagen')) {
            $data['imagen_path'] = $request->file('imagen')->store('instituciones-educativas/imagenes', 'public');
        }
        unset($data['imagen']);

        InstitucionEducativa::create($data);

        return redirect()->route('dashboard.instituciones-educativas.index')
            ->with('success', 'Institución educativa registrada correctamente.');
    }

    public function show(InstitucionEducativa $institucion)
    {
        abort_unless($institucion->hogar_id === $this->hogarId(), 403);

        $institucion->load([
            'empresa',
            'tipoInstitucionEducativa',
            'matriculas.hogarMiembro.user',
            'matriculas.nivelEducativo',
            'matriculas.estadoMatricula',
        ]);

        return view('dashboard.instituciones-educativas.show', compact('institucion'));
    }

    public function edit(InstitucionEducativa $institucion)
    {
        abort_unless($institucion->hogar_id === $this->hogarId(), 403);

        $tipos    = TipoInstitucionEducativa::orderBy('nombre')->get();
        $empresas = Empresa::orderBy('razon_social')->get();

        return view('dashboard.instituciones-educativas.edit', compact('institucion', 'tipos', 'empresas'));
    }

    public function update(UpdateInstitucionEducativaRequest $request, InstitucionEducativa $institucion)
    {
        abort_unless($institucion->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('logo')) {
            if ($institucion->logo_path) {
                Storage::disk('public')->delete($institucion->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('instituciones-educativas/logos', 'public');
        }
        unset($data['logo']);

        if ($request->hasFile('imagen')) {
            if ($institucion->imagen_path) {
                Storage::disk('public')->delete($institucion->imagen_path);
            }
            $data['imagen_path'] = $request->file('imagen')->store('instituciones-educativas/imagenes', 'public');
        }
        unset($data['imagen']);

        $institucion->update($data);

        return redirect()->route('dashboard.instituciones-educativas.show', $institucion)
            ->with('success', 'Institución educativa actualizada correctamente.');
    }

    public function destroy(InstitucionEducativa $institucion)
    {
        abort_unless($institucion->hogar_id === $this->hogarId(), 403);

        if ($institucion->logo_path) {
            Storage::disk('public')->delete($institucion->logo_path);
        }
        if ($institucion->imagen_path) {
            Storage::disk('public')->delete($institucion->imagen_path);
        }

        $institucion->delete();

        return redirect()->route('dashboard.instituciones-educativas.index')
            ->with('success', 'Institución educativa eliminada correctamente.');
    }
}

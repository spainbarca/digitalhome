<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCentroMedicoRequest;
use App\Http\Requests\UpdateCentroMedicoRequest;
use App\Models\CentroMedico;
use App\Models\Empresa;
use App\Models\TipoCentroMedico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CentroMedicoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizarCentro(CentroMedico $centro): void
    {
        abort_unless($centro->hogar_id === $this->hogarId(), 403);
    }

    public function index(Request $request): View
    {
        $hogarId = $this->hogarId();
        $search  = $request->get('search', '');
        $tipoId  = $request->get('tipo_centro_medico_id', '');

        $centros = CentroMedico::with(['empresa', 'tipoCentroMedico'])
            ->where('hogar_id', $hogarId)
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('nombre_referencial', 'like', "%{$search}%")
                  ->orWhereHas('empresa', fn ($e) => $e
                      ->where('razon_social', 'like', "%{$search}%")
                      ->orWhere('ruc', 'like', "%{$search}%")
                  );
            }))
            ->when($tipoId, fn ($q) => $q->where('tipo_centro_medico_id', $tipoId))
            ->orderBy('nombre_referencial')
            ->paginate(15)
            ->withQueryString();

        $tiposCentro = TipoCentroMedico::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.centros-medicos.index', compact('centros', 'search', 'tipoId', 'tiposCentro'));
    }

    public function create(): View
    {
        $tiposCentro = TipoCentroMedico::where('activo', true)->orderBy('nombre')->get();
        $empresas    = Empresa::orderBy('razon_social')->get();
        return view('dashboard.centros-medicos.create', compact('tiposCentro', 'empresas'));
    }

    public function store(StoreCentroMedicoRequest $request): RedirectResponse
    {
        $data             = $request->safe()->except(['imagen']);
        $data['hogar_id'] = $this->hogarId();
        $data['activo']   = $request->boolean('activo');

        if ($request->hasFile('imagen')) {
            $data['imagen_path'] = $request->file('imagen')->store('centros_medicos', 'public');
        }

        $centro = CentroMedico::create($data);

        return redirect()->route('dashboard.centros-medicos.show', $centro)
            ->with('success', 'Centro médico registrado correctamente.');
    }

    public function show(CentroMedico $centro): View
    {
        $this->autorizarCentro($centro);
        $centro->load(['empresa.sector', 'tipoCentroMedico', 'consultasMedicas.hogarMiembro', 'consultasMedicas.medico']);
        return view('dashboard.centros-medicos.show', compact('centro'));
    }

    public function edit(CentroMedico $centro): View
    {
        $this->autorizarCentro($centro);
        $tiposCentro = TipoCentroMedico::where('activo', true)->orderBy('nombre')->get();
        $empresas    = Empresa::orderBy('razon_social')->get();
        return view('dashboard.centros-medicos.edit', compact('centro', 'tiposCentro', 'empresas'));
    }

    public function update(UpdateCentroMedicoRequest $request, CentroMedico $centro): RedirectResponse
    {
        $this->autorizarCentro($centro);
        $data           = $request->safe()->except(['imagen']);
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('imagen')) {
            if ($centro->imagen_path) {
                Storage::disk('public')->delete($centro->imagen_path);
            }
            $data['imagen_path'] = $request->file('imagen')->store('centros_medicos', 'public');
        }

        $centro->update($data);

        return redirect()->route('dashboard.centros-medicos.show', $centro)
            ->with('success', 'Centro médico actualizado correctamente.');
    }

    public function destroy(CentroMedico $centro): RedirectResponse
    {
        $this->autorizarCentro($centro);

        if ($centro->imagen_path) {
            Storage::disk('public')->delete($centro->imagen_path);
        }

        $centro->delete();

        return redirect()->route('dashboard.centros-medicos.index')
            ->with('success', 'Centro médico eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicoRequest;
use App\Http\Requests\UpdateMedicoRequest;
use App\Models\EspecialidadMedica;
use App\Models\Medico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MedicoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizarMedico(Medico $medico): void
    {
        abort_unless($medico->hogar_id === $this->hogarId(), 403);
    }

    public function index(Request $request): View
    {
        $hogarId       = $this->hogarId();
        $search        = $request->get('search', '');
        $especialidadId = $request->get('especialidad_medica_id', '');

        $medicos = Medico::with('especialidadMedica')
            ->where('hogar_id', $hogarId)
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('cmp', 'like', "%{$search}%");
            }))
            ->when($especialidadId, fn ($q) => $q->where('especialidad_medica_id', $especialidadId))
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate(15)
            ->withQueryString();

        $especialidades = EspecialidadMedica::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.medicos.index', compact('medicos', 'search', 'especialidadId', 'especialidades'));
    }

    public function create(): View
    {
        $especialidades = EspecialidadMedica::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.medicos.create', compact('especialidades'));
    }

    public function store(StoreMedicoRequest $request): RedirectResponse
    {
        $data             = $request->safe()->except(['imagen']);
        $data['hogar_id'] = $this->hogarId();
        $data['activo']   = $request->boolean('activo');

        if ($request->hasFile('imagen')) {
            $data['imagen_path'] = $request->file('imagen')->store('medicos', 'public');
        }

        $medico = Medico::create($data);

        return redirect()->route('dashboard.medicos.show', $medico)
            ->with('success', 'Médico registrado correctamente.');
    }

    public function show(Medico $medico): View
    {
        $this->autorizarMedico($medico);
        $medico->load(['especialidadMedica', 'consultasMedicas.hogarMiembro']);
        return view('dashboard.medicos.show', compact('medico'));
    }

    public function edit(Medico $medico): View
    {
        $this->autorizarMedico($medico);
        $especialidades = EspecialidadMedica::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.medicos.edit', compact('medico', 'especialidades'));
    }

    public function update(UpdateMedicoRequest $request, Medico $medico): RedirectResponse
    {
        $this->autorizarMedico($medico);
        $data           = $request->safe()->except(['imagen']);
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('imagen')) {
            if ($medico->imagen_path) {
                Storage::disk('public')->delete($medico->imagen_path);
            }
            $data['imagen_path'] = $request->file('imagen')->store('medicos', 'public');
        }

        $medico->update($data);

        return redirect()->route('dashboard.medicos.show', $medico)
            ->with('success', 'Médico actualizado correctamente.');
    }

    public function destroy(Medico $medico): RedirectResponse
    {
        $this->autorizarMedico($medico);

        if ($medico->imagen_path) {
            Storage::disk('public')->delete($medico->imagen_path);
        }

        $medico->delete();

        return redirect()->route('dashboard.medicos.index')
            ->with('success', 'Médico eliminado correctamente.');
    }
}

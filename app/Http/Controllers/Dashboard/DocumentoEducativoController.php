<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentoEducativoRequest;
use App\Http\Requests\UpdateDocumentoEducativoRequest;
use App\Models\DocumentoEducativo;
use App\Models\HogarMiembro;
use App\Models\Matricula;
use App\Models\TipoDocumentoEducativo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentoEducativoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizar(DocumentoEducativo $documento): void
    {
        abort_unless($documento->hogar_id === $this->hogarId(), 403);
    }

    private function miembrosHogar(): \Illuminate\Support\Collection
    {
        return HogarMiembro::where('hogar_id', $this->hogarId())
            ->with('user.persona')
            ->get()
            ->sortBy(fn ($m) => trim(
                ($m->user?->persona?->nombres ?? '') . ' ' .
                ($m->user?->persona?->apellido_paterno ?? '')
            ));
    }

    public function index(Request $request): View
    {
        $hogarId    = $this->hogarId();
        $miembroId  = $request->get('hogar_miembro_id', '');
        $tipoId     = $request->get('tipo_documento_educativo_id', '');
        $matriculaId = $request->get('matricula_id', '');
        $fechaDesde = $request->get('fecha_desde', '');
        $fechaHasta = $request->get('fecha_hasta', '');

        $documentos = DocumentoEducativo::with([
                'hogarMiembro.user.persona',
                'tipoDocumentoEducativo',
                'matricula.institucionEducativa',
            ])
            ->where('hogar_id', $hogarId)
            ->when($miembroId, fn ($q) => $q->where('hogar_miembro_id', $miembroId))
            ->when($tipoId, fn ($q) => $q->where('tipo_documento_educativo_id', $tipoId))
            ->when($matriculaId, fn ($q) => $q->where('matricula_id', $matriculaId))
            ->when($fechaDesde, fn ($q) => $q->where('fecha_documento', '>=', $fechaDesde))
            ->when($fechaHasta, fn ($q) => $q->where('fecha_documento', '<=', $fechaHasta))
            ->orderBy('fecha_documento', 'desc')
            ->paginate(15)
            ->withQueryString();

        $miembros       = $this->miembrosHogar();
        $tiposDocumento = TipoDocumentoEducativo::where('activo', true)->orderBy('nombre')->get();
        $matriculas     = Matricula::where('hogar_id', $hogarId)
            ->with(['hogarMiembro.user.persona', 'institucionEducativa'])
            ->orderByDesc('año_lectivo')
            ->get();

        return view('dashboard.documentos-educativos.index', compact(
            'documentos', 'miembros', 'tiposDocumento', 'matriculas',
            'miembroId', 'tipoId', 'matriculaId', 'fechaDesde', 'fechaHasta'
        ));
    }

    public function create(Request $request): View
    {
        $miembros       = $this->miembrosHogar();
        $tiposDocumento = TipoDocumentoEducativo::where('activo', true)->orderBy('nombre')->get();

        $matriculaPresel = null;
        $miembroPresel   = $request->get('hogar_miembro_id', '');
        if ($preselId = $request->get('matricula_id')) {
            $matriculaPresel = Matricula::where('hogar_id', $this->hogarId())->find($preselId);
            if ($matriculaPresel && !$miembroPresel) {
                $miembroPresel = $matriculaPresel->hogar_miembro_id;
            }
        }

        return view('dashboard.documentos-educativos.create', compact(
            'miembros', 'tiposDocumento', 'matriculaPresel', 'miembroPresel'
        ));
    }

    public function store(StoreDocumentoEducativoRequest $request): RedirectResponse
    {
        $data             = $request->safe()->except(['archivo']);
        $data['hogar_id'] = $this->hogarId();

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $data['archivo_path']            = $file->store('documentos-educativos', 'public');
            $data['archivo_nombre_original'] = $file->getClientOriginalName();
            $data['archivo_mime']            = $file->getMimeType();
            $data['archivo_size']            = $file->getSize();
        }

        $documento = DocumentoEducativo::create($data);

        return redirect()->route('dashboard.documentos-educativos.show', $documento)
            ->with('success', 'Documento educativo subido correctamente.');
    }

    public function show(DocumentoEducativo $documento): View
    {
        $this->autorizar($documento);
        $documento->load([
            'hogarMiembro.user.persona',
            'tipoDocumentoEducativo',
            'matricula.institucionEducativa',
        ]);

        return view('dashboard.documentos-educativos.show', compact('documento'));
    }

    public function edit(DocumentoEducativo $documento): View
    {
        $this->autorizar($documento);
        $hogarId        = $this->hogarId();
        $miembros       = $this->miembrosHogar();
        $tiposDocumento = TipoDocumentoEducativo::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.documentos-educativos.edit', compact(
            'documento', 'miembros', 'tiposDocumento'
        ));
    }

    public function update(UpdateDocumentoEducativoRequest $request, DocumentoEducativo $documento): RedirectResponse
    {
        $this->autorizar($documento);

        $data = $request->safe()->except(['archivo']);

        if ($request->hasFile('archivo')) {
            if ($documento->archivo_path) {
                Storage::disk('public')->delete($documento->archivo_path);
            }
            $file = $request->file('archivo');
            $data['archivo_path']            = $file->store('documentos-educativos', 'public');
            $data['archivo_nombre_original'] = $file->getClientOriginalName();
            $data['archivo_mime']            = $file->getMimeType();
            $data['archivo_size']            = $file->getSize();
        }

        $documento->update($data);

        return redirect()->route('dashboard.documentos-educativos.show', $documento)
            ->with('success', 'Documento educativo actualizado correctamente.');
    }

    public function destroy(DocumentoEducativo $documento): RedirectResponse
    {
        $this->autorizar($documento);

        if ($documento->archivo_path) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $documento->delete();

        return redirect()->route('dashboard.documentos-educativos.index')
            ->with('success', 'Documento educativo eliminado correctamente.');
    }

    public function porMiembro(HogarMiembro $miembro): JsonResponse
    {
        abort_unless($miembro->hogar_id === $this->hogarId(), 403);

        $matriculas = Matricula::where('hogar_id', $this->hogarId())
            ->where('hogar_miembro_id', $miembro->id)
            ->with(['institucionEducativa', 'nivelEducativo'])
            ->orderByDesc('año_lectivo')
            ->get()
            ->map(fn ($m) => [
                'id'    => $m->id,
                'label' => ($m->institucionEducativa?->nombre_referencial ?? '(Sin institución)')
                    . ($m->año_lectivo ? ' — ' . $m->año_lectivo : '')
                    . ($m->grado_ciclo ? ' · ' . $m->grado_ciclo : ''),
            ]);

        return response()->json($matriculas);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentoMedicoRequest;
use App\Http\Requests\UpdateDocumentoMedicoRequest;
use App\Models\ConsultaMedica;
use App\Models\DocumentoMedico;
use App\Models\HogarMiembro;
use App\Models\TipoDocumentoMedico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentoMedicoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizarDocumento(DocumentoMedico $documento): void
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

    private static function nombreMiembro(\App\Models\HogarMiembro $m): string
    {
        $nombre = trim(implode(' ', array_filter([
            $m->user?->persona?->nombres,
            $m->user?->persona?->apellido_paterno,
            $m->user?->persona?->apellido_materno,
        ])));
        return $nombre ?: ($m->user?->name ?? 'Sin nombre');
    }

    public function index(Request $request): View
    {
        $hogarId   = $this->hogarId();
        $miembroId = $request->get('hogar_miembro_id', '');
        $tipoId    = $request->get('tipo_documento_medico_id', '');
        $consultaId = $request->get('consulta_medica_id', '');
        $fechaDesde = $request->get('fecha_desde', '');
        $fechaHasta = $request->get('fecha_hasta', '');

        $documentos = DocumentoMedico::with(['hogarMiembro.user.persona', 'tipoDocumentoMedico', 'consultaMedica'])
            ->where('hogar_id', $hogarId)
            ->when($miembroId, fn ($q) => $q->where('hogar_miembro_id', $miembroId))
            ->when($tipoId, fn ($q) => $q->where('tipo_documento_medico_id', $tipoId))
            ->when($consultaId, fn ($q) => $q->where('consulta_medica_id', $consultaId))
            ->when($fechaDesde, fn ($q) => $q->where('fecha_documento', '>=', $fechaDesde))
            ->when($fechaHasta, fn ($q) => $q->where('fecha_documento', '<=', $fechaHasta))
            ->orderBy('fecha_documento', 'desc')
            ->paginate(15)
            ->withQueryString();

        $miembros       = $this->miembrosHogar();
        $tiposDocumento = TipoDocumentoMedico::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.documentos-medicos.index', compact(
            'documentos', 'miembros', 'tiposDocumento',
            'miembroId', 'tipoId', 'consultaId', 'fechaDesde', 'fechaHasta'
        ));
    }

    public function create(Request $request): View
    {
        $miembros       = $this->miembrosHogar();
        $tiposDocumento = TipoDocumentoMedico::where('activo', true)->orderBy('nombre')->get();

        // Si viene con consulta pre-seleccionada (desde show de consulta), cargamos el objeto
        // para que el JS pueda pre-seleccionar miembro + consulta en el formulario
        $consultaPresel = null;
        if ($preselId = $request->get('consulta_medica_id')) {
            $consultaPresel = ConsultaMedica::where('hogar_id', $this->hogarId())->find($preselId);
        }

        return view('dashboard.documentos-medicos.create', compact(
            'miembros', 'tiposDocumento', 'consultaPresel'
        ));
    }

    public function store(StoreDocumentoMedicoRequest $request): RedirectResponse
    {
        $data             = $request->safe()->except(['archivo']);
        $data['hogar_id'] = $this->hogarId();

        if ($request->hasFile('archivo')) {
            $file                         = $request->file('archivo');
            $data['archivo_path']         = $file->store('documentos-medicos', 'public');
            $data['archivo_nombre_original'] = $file->getClientOriginalName();
            $data['archivo_mime']         = $file->getMimeType();
            $data['archivo_size']         = $file->getSize();
        }

        $documento = DocumentoMedico::create($data);

        return redirect()->route('dashboard.documentos-medicos.show', $documento)
            ->with('success', 'Documento médico subido correctamente.');
    }

    public function show(DocumentoMedico $documento): View
    {
        $this->autorizarDocumento($documento);
        $documento->load(['hogarMiembro.user.persona', 'tipoDocumentoMedico', 'consultaMedica.medico']);

        return view('dashboard.documentos-medicos.show', compact('documento'));
    }

    public function edit(DocumentoMedico $documento): View
    {
        $this->autorizarDocumento($documento);

        $miembros       = $this->miembrosHogar();
        $tiposDocumento = TipoDocumentoMedico::where('activo', true)->orderBy('nombre')->get();
        $consultas      = ConsultaMedica::where('hogar_id', $this->hogarId())
                            ->with('hogarMiembro.user.persona')
                            ->orderBy('fecha', 'desc')
                            ->get();

        return view('dashboard.documentos-medicos.edit', compact(
            'documento', 'miembros', 'tiposDocumento', 'consultas'
        ));
    }

    public function update(UpdateDocumentoMedicoRequest $request, DocumentoMedico $documento): RedirectResponse
    {
        $this->autorizarDocumento($documento);

        $data = $request->safe()->except(['archivo']);

        if ($request->hasFile('archivo')) {
            if ($documento->archivo_path) {
                Storage::disk('public')->delete($documento->archivo_path);
            }
            $file                         = $request->file('archivo');
            $data['archivo_path']         = $file->store('documentos-medicos', 'public');
            $data['archivo_nombre_original'] = $file->getClientOriginalName();
            $data['archivo_mime']         = $file->getMimeType();
            $data['archivo_size']         = $file->getSize();
        }

        $documento->update($data);

        return redirect()->route('dashboard.documentos-medicos.show', $documento)
            ->with('success', 'Documento médico actualizado correctamente.');
    }

    public function destroy(DocumentoMedico $documento): RedirectResponse
    {
        $this->autorizarDocumento($documento);

        if ($documento->archivo_path) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $documento->delete();

        return redirect()->route('dashboard.documentos-medicos.index')
            ->with('success', 'Documento médico eliminado correctamente.');
    }
}

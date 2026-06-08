<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentoLegalRequest;
use App\Http\Requests\UpdateDocumentoLegalRequest;
use App\Models\DocumentoLegal;
use App\Models\EntidadLegal;
use App\Models\EstadoDocumentoLegal;
use App\Models\HogarMiembro;
use App\Models\PropiedadInmueble;
use App\Models\TipoDocumentoLegal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentoLegalController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizarDocumento(DocumentoLegal $documento): void
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

    private function propiedadesHogar(): \Illuminate\Support\Collection
    {
        return PropiedadInmueble::whereHas('persona', fn ($q) => $q->where('hogar_id', $this->hogarId()))
            ->with('tipoInmueble')
            ->orderBy('alias')
            ->get();
    }

    public static function nombreMiembro(HogarMiembro $m): string
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
        $hogarId     = $this->hogarId();
        $categoria   = $request->get('categoria', '');
        $miembroId   = $request->get('hogar_miembro_id', '');
        $propiedadId = $request->get('propiedad_inmueble_id', '');

        $documentos = DocumentoLegal::with([
                'tipoDocumentoLegal',
                'estadoDocumentoLegal',
                'hogarMiembro.user.persona',
                'propiedadInmueble.tipoInmueble',
            ])
            ->where('hogar_id', $hogarId)
            ->when($categoria, fn ($q) => $q->whereHas('tipoDocumentoLegal', fn ($sq) => $sq->where('categoria', $categoria)))
            ->when($miembroId, fn ($q) => $q->where('hogar_miembro_id', $miembroId))
            ->when($propiedadId === 'none', fn ($q) => $q->whereNull('propiedad_inmueble_id'))
            ->when($propiedadId && $propiedadId !== 'none', fn ($q) => $q->where('propiedad_inmueble_id', $propiedadId))
            ->orderBy('fecha_vencimiento')
            ->orderBy('titulo')
            ->paginate(15)
            ->withQueryString();

        $miembros    = $this->miembrosHogar();
        $propiedades = $this->propiedadesHogar();

        $categorias = [
            'personal'  => 'Personal',
            'propiedad' => 'Propiedad',
            'seguro'    => 'Seguro',
            'contrato'  => 'Contrato',
            'denuncia'  => 'Denuncia',
            'otro'      => 'Otro',
        ];

        return view('dashboard.documentos-legales.index', compact(
            'documentos', 'miembros', 'propiedades', 'categorias',
            'categoria', 'miembroId', 'propiedadId'
        ));
    }

    public function create(): View
    {
        $tipos     = TipoDocumentoLegal::where('activo', true)->orderBy('categoria')->orderBy('nombre')->get();
        $estados   = EstadoDocumentoLegal::orderBy('nombre')->get();
        $miembros  = $this->miembrosHogar();
        $propiedades = $this->propiedadesHogar();
        $entidades = EntidadLegal::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.documentos-legales.create', compact(
            'tipos', 'estados', 'miembros', 'propiedades', 'entidades'
        ));
    }

    public function store(StoreDocumentoLegalRequest $request): RedirectResponse
    {
        $data             = $request->safe()->except(['archivo']);
        $data['hogar_id'] = $this->hogarId();
        $data['activo']   = $request->boolean('activo');

        if ($request->hasFile('archivo')) {
            $data['file_path'] = $request->file('archivo')->store('documentos-legales', 'public');
        }

        $documento = DocumentoLegal::create($data);

        return redirect()->route('dashboard.documentos-legales.show', $documento)
            ->with('success', 'Documento legal registrado correctamente.');
    }

    public function show(DocumentoLegal $documentoLegal): View
    {
        $this->autorizarDocumento($documentoLegal);
        $documentoLegal->load([
            'tipoDocumentoLegal',
            'estadoDocumentoLegal',
            'hogarMiembro.user.persona',
            'propiedadInmueble.tipoInmueble',
            'entidadLegal',
        ]);
        return view('dashboard.documentos-legales.show', compact('documentoLegal'));
    }

    public function edit(DocumentoLegal $documentoLegal): View
    {
        $this->autorizarDocumento($documentoLegal);

        $tipos     = TipoDocumentoLegal::where('activo', true)->orderBy('categoria')->orderBy('nombre')->get();
        $estados   = EstadoDocumentoLegal::orderBy('nombre')->get();
        $miembros  = $this->miembrosHogar();
        $propiedades = $this->propiedadesHogar();
        $entidades = EntidadLegal::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.documentos-legales.edit', compact(
            'documentoLegal', 'tipos', 'estados', 'miembros', 'propiedades', 'entidades'
        ));
    }

    public function update(UpdateDocumentoLegalRequest $request, DocumentoLegal $documentoLegal): RedirectResponse
    {
        $this->autorizarDocumento($documentoLegal);

        $data           = $request->safe()->except(['archivo']);
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('archivo')) {
            if ($documentoLegal->file_path) {
                Storage::disk('public')->delete($documentoLegal->file_path);
            }
            $data['file_path'] = $request->file('archivo')->store('documentos-legales', 'public');
        }

        $documentoLegal->update($data);

        return redirect()->route('dashboard.documentos-legales.show', $documentoLegal)
            ->with('success', 'Documento legal actualizado correctamente.');
    }

    public function destroy(DocumentoLegal $documentoLegal): RedirectResponse
    {
        $this->autorizarDocumento($documentoLegal);

        if ($documentoLegal->file_path) {
            Storage::disk('public')->delete($documentoLegal->file_path);
        }

        $documentoLegal->delete();

        return redirect()->route('dashboard.documentos-legales.index')
            ->with('success', 'Documento legal eliminado correctamente.');
    }
}

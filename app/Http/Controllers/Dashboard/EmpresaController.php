<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Models\Empresa;
use App\Models\Sector;
use App\Models\TipoContribuyente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmpresaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');

        $empresas = Empresa::with(['sector', 'distrito', 'tipoContribuyente'])
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('razon_social', 'like', "%{$search}%")
                  ->orWhere('ruc', 'like', "%{$search}%");
            }))
            ->orderBy('razon_social')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.empresas.index', compact('empresas', 'search'));
    }

    public function create(): View
    {
        $this->authorize('create', Empresa::class);
        $sectores           = Sector::where('activo', true)->orderBy('nombre')->get();
        $tiposContribuyente = TipoContribuyente::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.empresas.create', compact('sectores', 'tiposContribuyente'));
    }

    public function store(StoreEmpresaRequest $request): RedirectResponse
    {
        $this->authorize('create', Empresa::class);

        $data           = $request->safe()->except(['logo']);
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('logo')) {
            $data['logo_url'] = $request->file('logo')->store('empresas', 'public');
        }

        if ($request->filled('sunat_sincronizado_en')) {
            $data['sunat_sincronizado_en'] = $request->input('sunat_sincronizado_en');
        }

        $empresa = Empresa::create($data);

        return redirect()->route('dashboard.empresas.show', $empresa)
            ->with('success', 'Empresa registrada correctamente.');
    }

    public function show(Empresa $empresa): View
    {
        $this->authorize('view', $empresa);
        $empresa->load(['sector', 'tipoContribuyente', 'distrito', 'proveedoresServicio.tipoServicio']);
        return view('dashboard.empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa): View
    {
        $this->authorize('update', $empresa);
        $sectores           = Sector::where('activo', true)->orderBy('nombre')->get();
        $tiposContribuyente = TipoContribuyente::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.empresas.edit', compact('empresa', 'sectores', 'tiposContribuyente'));
    }

    public function update(UpdateEmpresaRequest $request, Empresa $empresa): RedirectResponse
    {
        $this->authorize('update', $empresa);

        $data           = $request->safe()->except(['logo']);
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('logo')) {
            if ($empresa->logo_url) {
                Storage::disk('public')->delete($empresa->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('empresas', 'public');
        }

        if ($request->filled('sunat_sincronizado_en')) {
            $data['sunat_sincronizado_en'] = $request->input('sunat_sincronizado_en');
        }

        $empresa->update($data);

        return redirect()->route('dashboard.empresas.show', $empresa)
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Empresa $empresa): RedirectResponse
    {
        $this->authorize('delete', $empresa);
        $empresa->delete();
        return redirect()->route('dashboard.empresas.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }

    public function buscarRuc(Request $request): JsonResponse
    {
        $ruc = trim($request->get('ruc', ''));

        if (strlen($ruc) !== 11 || !ctype_digit($ruc)) {
            return response()->json(['error' => 'El RUC debe tener exactamente 11 dígitos numéricos.'], 422);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(['X-API-KEY' => env('PERUAPI_KEY')])
                ->get("https://peruapi.com/api/ruc/{$ruc}");

            if ($response->successful()) {
                $api = $response->json();

                if (($api['code'] ?? '') !== '200') {
                    return response()->json(['error' => 'RUC no encontrado o servicio no disponible.'], 404);
                }

                return response()->json([
                    'razon_social'             => $api['razon_social'] ?? '',
                    'nombre_comercial'         => $api['razon_social'] ?? '',
                    'estado_sunat'             => $api['estado']       ?? '',
                    'condicion_sunat'          => $api['condicion']    ?? '',
                    'direccion_fiscal'         => $api['direccion']    ?? '',
                    'distrito_inei'            => $api['ubigeo']       ?? '',
                    'fecha_inicio_actividades' => '',
                    'departamento'             => $api['departamento'] ?? '',
                    'provincia'                => $api['provincia']    ?? '',
                    'distrito'                 => $api['distrito']     ?? '',
                ]);
            }

            return response()->json(['error' => 'RUC no encontrado o servicio no disponible.'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error de conexión con el servicio PeruAPI. Inténtalo de nuevo.'], 503);
        }
    }
}

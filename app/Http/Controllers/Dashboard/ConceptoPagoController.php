<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConceptoPagoRequest;
use App\Http\Requests\UpdateConceptoPagoRequest;
use App\Models\CategoriaConcepto;
use App\Models\ConceptoPago;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConceptoPagoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function index(Request $request)
    {
        $hogarId = $this->hogarId();

        $query = ConceptoPago::with('categoriaConcepto')
            ->where('hogar_id', $hogarId);

        if ($request->filled('q')) {
            $query->where('nombre', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_concepto_id', $request->categoria_id);
        }

        $conceptos  = $query->orderBy('nombre')->paginate(15)->withQueryString();
        $categorias = CategoriaConcepto::where('activo', true)->orderBy('nombre')->get();

        return view('dashboard.conceptos-pago.index', compact('conceptos', 'categorias'));
    }

    public function create()
    {
        $categorias = CategoriaConcepto::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.conceptos-pago.create', compact('categorias'));
    }

    public function store(StoreConceptoPagoRequest $request)
    {
        $data             = $request->validated();
        $data['hogar_id'] = $this->hogarId();
        $data['activo']   = $request->boolean('activo');

        if ($request->hasFile('imagen')) {
            $file                = $request->file('imagen');
            $uuid                = Str::uuid();
            $ext                 = $file->getClientOriginalExtension();
            $data['imagen_path'] = $file->storeAs("conceptos_pago/{$uuid}", "imagen.{$ext}", 'public');
        }
        unset($data['imagen']);

        $concepto = ConceptoPago::create($data);

        return redirect()->route('dashboard.prestamos.conceptos-pago.show', $concepto)
            ->with('success', 'Concepto de pago registrado correctamente.');
    }

    public function show(ConceptoPago $conceptoPago)
    {
        abort_unless($conceptoPago->hogar_id === $this->hogarId(), 403);
        $conceptoPago->load('categoriaConcepto');
        return view('dashboard.conceptos-pago.show', compact('conceptoPago'));
    }

    public function edit(ConceptoPago $conceptoPago)
    {
        abort_unless($conceptoPago->hogar_id === $this->hogarId(), 403);
        $categorias = CategoriaConcepto::where('activo', true)->orderBy('nombre')->get();
        return view('dashboard.conceptos-pago.edit', compact('conceptoPago', 'categorias'));
    }

    public function update(UpdateConceptoPagoRequest $request, ConceptoPago $conceptoPago)
    {
        abort_unless($conceptoPago->hogar_id === $this->hogarId(), 403);

        $data           = $request->validated();
        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('imagen')) {
            if ($conceptoPago->imagen_path) {
                Storage::disk('public')->deleteDirectory(dirname($conceptoPago->imagen_path));
            }
            $file                = $request->file('imagen');
            $uuid                = Str::uuid();
            $ext                 = $file->getClientOriginalExtension();
            $data['imagen_path'] = $file->storeAs("conceptos_pago/{$uuid}", "imagen.{$ext}", 'public');
        }
        unset($data['imagen']);

        $conceptoPago->update($data);

        return redirect()->route('dashboard.prestamos.conceptos-pago.show', $conceptoPago)
            ->with('success', 'Concepto de pago actualizado correctamente.');
    }

    public function destroy(ConceptoPago $conceptoPago)
    {
        abort_unless($conceptoPago->hogar_id === $this->hogarId(), 403);

        if ($conceptoPago->imagen_path) {
            Storage::disk('public')->deleteDirectory(dirname($conceptoPago->imagen_path));
        }

        $conceptoPago->delete();

        return redirect()->route('dashboard.prestamos.conceptos-pago.index')
            ->with('success', 'Concepto de pago eliminado correctamente.');
    }

    public function toggleActivo(ConceptoPago $conceptoPago): JsonResponse
    {
        abort_unless($conceptoPago->hogar_id === $this->hogarId(), 403);
        $conceptoPago->update(['activo' => !$conceptoPago->activo]);
        return response()->json(['activo' => $conceptoPago->activo]);
    }
}

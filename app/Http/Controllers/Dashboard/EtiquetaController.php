<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEtiquetaRequest;
use App\Http\Requests\UpdateEtiquetaRequest;
use App\Models\Etiqueta;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EtiquetaController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function autorizarEtiqueta(Etiqueta $etiqueta): void
    {
        abort_unless($etiqueta->hogar_id === $this->hogarId(), 403);
    }

    public function index(): View
    {
        $etiquetas = Etiqueta::where('hogar_id', $this->hogarId())
            ->orderBy('nombre')
            ->get();

        return view('dashboard.etiquetas.index', compact('etiquetas'));
    }

    public function store(StoreEtiquetaRequest $request): JsonResponse
    {
        try {
            $hogarId = $this->hogarId();

            if (!$hogarId) {
                return response()->json(['success' => false, 'message' => 'No se encontró un hogar asociado a tu cuenta.'], 422);
            }

            $etiqueta = Etiqueta::create([
                'hogar_id' => $hogarId,
                'nombre'   => $request->nombre,
                'color'    => $request->color,
                'icono'    => $request->icono ?: null,
            ]);

            return response()->json(['success' => true, 'data' => $etiqueta]);
        } catch (\Exception $e) {
            Log::error('EtiquetaController@store: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateEtiquetaRequest $request, Etiqueta $etiqueta): JsonResponse
    {
        try {
            $this->autorizarEtiqueta($etiqueta);

            $etiqueta->update([
                'nombre' => $request->nombre,
                'color'  => $request->color,
                'icono'  => $request->icono ?: null,
            ]);

            return response()->json(['success' => true, 'data' => $etiqueta]);
        } catch (\Exception $e) {
            Log::error('EtiquetaController@update: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Etiqueta $etiqueta): JsonResponse
    {
        $this->autorizarEtiqueta($etiqueta);

        DB::table('etiquetables')->where('etiqueta_id', $etiqueta->id)->delete();
        $etiqueta->delete();

        return response()->json(['success' => true]);
    }
}

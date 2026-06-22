<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChecklistViaje;
use App\Models\Viaje;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistViajeController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(Request $request, Viaje $viaje): JsonResponse
    {
        abort_unless($viaje->hogar_id === $this->hogarId(), 403);

        $data = $request->validate([
            'descripcion'      => ['required', 'string', 'max:500'],
            'hogar_miembro_id' => ['nullable', 'exists:hogar_miembros,id'],
            'fecha_limite'     => ['nullable', 'date'],
            'orden'            => ['nullable', 'integer', 'min:0'],
            'notas'            => ['nullable', 'string'],
        ]);

        $data['viaje_id']   = $viaje->id;
        $data['completado'] = false;
        $data['orden']      = $data['orden'] ?? ($viaje->checklist()->max('orden') + 1 ?? 1);

        $item = ChecklistViaje::create($data);
        $item->load('hogarMiembro.user.persona');

        return response()->json(['success' => true, 'data' => $this->formatItem($item)]);
    }

    public function update(Request $request, ChecklistViaje $checklistViaje): JsonResponse
    {
        abort_unless(
            $checklistViaje->viaje?->hogar_id === $this->hogarId(),
            403
        );

        $data = $request->validate([
            'descripcion'      => ['required', 'string', 'max:500'],
            'hogar_miembro_id' => ['nullable', 'exists:hogar_miembros,id'],
            'fecha_limite'     => ['nullable', 'date'],
            'orden'            => ['nullable', 'integer', 'min:0'],
            'notas'            => ['nullable', 'string'],
        ]);

        $checklistViaje->update($data);
        $checklistViaje->load('hogarMiembro.user.persona');

        return response()->json(['success' => true, 'data' => $this->formatItem($checklistViaje->fresh())]);
    }

    public function destroy(ChecklistViaje $checklistViaje): JsonResponse
    {
        abort_unless(
            $checklistViaje->viaje?->hogar_id === $this->hogarId(),
            403
        );

        $checklistViaje->delete();
        return response()->json(['success' => true]);
    }

    public function toggle(ChecklistViaje $checklistViaje): JsonResponse
    {
        abort_unless(
            $checklistViaje->viaje?->hogar_id === $this->hogarId(),
            403
        );

        $checklistViaje->update(['completado' => !$checklistViaje->completado]);

        return response()->json([
            'success'    => true,
            'completado' => $checklistViaje->fresh()->completado,
        ]);
    }

    private function formatItem(ChecklistViaje $item): array
    {
        $persona = $item->hogarMiembro?->user?->persona;
        $nombre  = $persona
            ? trim(implode(' ', array_filter([$persona->nombres, $persona->apellido_paterno])))
            : null;

        return [
            'id'               => $item->id,
            'descripcion'      => $item->descripcion,
            'completado'       => $item->completado,
            'hogar_miembro_id' => $item->hogar_miembro_id,
            'miembro_nombre'   => $nombre,
            'fecha_limite'     => $item->fecha_limite?->format('Y-m-d'),
            'fecha_limite_fmt' => $item->fecha_limite?->format('d/m/Y'),
            'orden'            => $item->orden,
            'notas'            => $item->notas,
        ];
    }
}

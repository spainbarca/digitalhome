<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Capacitacion;
use App\Models\Empleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CapacitacionController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function rules(): array
    {
        return [
            'tipo_capacitacion_id'     => ['nullable', 'exists:tipo_capacitacion,id'],
            'institucion_educativa_id' => ['nullable', 'exists:instituciones_educativas,id'],
            'nombre'                   => ['required', 'string', 'max:255'],
            'descripcion'              => ['nullable', 'string'],
            'institucion_nombre'       => ['nullable', 'string', 'max:255'],
            'fecha_inicio'             => ['nullable', 'date'],
            'fecha_fin'                => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'fecha_vencimiento'        => ['nullable', 'date'],
            'notas'                    => ['nullable', 'string'],
            'archivo'                  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'activo'                   => ['nullable', 'boolean'],
        ];
    }

    public function store(Request $request, Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $data = $request->validate($this->rules());
        $data['hogar_id']          = $empleo->hogar_id;
        $data['hogar_miembro_id']  = $empleo->hogar_miembro_id;

        if ($request->hasFile('archivo')) {
            $data['file_path'] = $request->file('archivo')->store('capacitaciones', 'public');
        }
        unset($data['archivo']);

        $empleo->capacitaciones()->create($data);

        return back()->with('success', 'Capacitación agregada correctamente.');
    }

    public function update(Request $request, Capacitacion $capacitacion)
    {
        abort_unless($capacitacion->empleo?->hogar_id === $this->hogarId(), 403);

        $data = $request->validate($this->rules());

        if ($request->hasFile('archivo')) {
            if ($capacitacion->file_path) Storage::disk('public')->delete($capacitacion->file_path);
            $data['file_path'] = $request->file('archivo')->store('capacitaciones', 'public');
        }
        unset($data['archivo']);

        $capacitacion->update($data);

        return back()->with('success', 'Capacitación actualizada correctamente.');
    }

    public function destroy(Capacitacion $capacitacion)
    {
        abort_unless($capacitacion->empleo?->hogar_id === $this->hogarId(), 403);

        if ($capacitacion->file_path) Storage::disk('public')->delete($capacitacion->file_path);
        $capacitacion->delete();

        return back()->with('success', 'Capacitación eliminada correctamente.');
    }
}

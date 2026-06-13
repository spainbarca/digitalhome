<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EmpleoReferencia;
use App\Models\Empleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleoReferenciaController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function rules(): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:255'],
            'cargo'    => ['nullable', 'string', 'max:255'],
            'relacion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email'    => ['nullable', 'email', 'max:150'],
            'activo'   => ['nullable', 'boolean'],
        ];
    }

    public function store(Request $request, Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $empleo->empleoReferencias()->create($request->validate($this->rules()));

        return back()->with('success', 'Referencia agregada correctamente.');
    }

    public function update(Request $request, EmpleoReferencia $referencia)
    {
        abort_unless($referencia->empleo?->hogar_id === $this->hogarId(), 403);

        $referencia->update($request->validate($this->rules()));

        return back()->with('success', 'Referencia actualizada correctamente.');
    }

    public function destroy(EmpleoReferencia $referencia)
    {
        abort_unless($referencia->empleo?->hogar_id === $this->hogarId(), 403);

        $referencia->delete();

        return back()->with('success', 'Referencia eliminada correctamente.');
    }
}

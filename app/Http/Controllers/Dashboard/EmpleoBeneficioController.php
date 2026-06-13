<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EmpleoBeneficio;
use App\Models\Empleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleoBeneficioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    private function rules(): array
    {
        return [
            'tipo_beneficio_id' => ['required', 'exists:tipo_beneficio,id'],
            'detalle'           => ['nullable', 'string'],
            'entidad'           => ['nullable', 'string', 'max:255'],
            'activo'            => ['nullable', 'boolean'],
        ];
    }

    public function store(Request $request, Empleo $empleo)
    {
        abort_unless($empleo->hogar_id === $this->hogarId(), 403);

        $empleo->empleoBeneficios()->create($request->validate($this->rules()));

        return back()->with('success', 'Beneficio agregado correctamente.');
    }

    public function update(Request $request, EmpleoBeneficio $beneficio)
    {
        abort_unless($beneficio->empleo?->hogar_id === $this->hogarId(), 403);

        $beneficio->update($request->validate($this->rules()));

        return back()->with('success', 'Beneficio actualizado correctamente.');
    }

    public function destroy(EmpleoBeneficio $beneficio)
    {
        abort_unless($beneficio->empleo?->hogar_id === $this->hogarId(), 403);

        $beneficio->delete();

        return back()->with('success', 'Beneficio eliminado correctamente.');
    }
}

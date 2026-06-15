<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovimientoPrestamoRequest;
use App\Http\Requests\UpdateMovimientoPrestamoRequest;
use App\Models\MovimientoPrestamo;
use App\Models\Prestatario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovimientoPrestamoController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(StoreMovimientoPrestamoRequest $request, Prestatario $prestatario)
    {
        abort_unless($prestatario->hogar_id === $this->hogarId(), 403);

        $data                   = $request->validated();
        $data['hogar_id']       = $prestatario->hogar_id;
        $data['prestatario_id'] = $prestatario->id;

        if ($request->hasFile('comprobante')) {
            $file                    = $request->file('comprobante');
            $uuid                    = Str::uuid();
            $ext                     = $file->getClientOriginalExtension();
            $data['comprobante_path'] = $file->storeAs("movimientos_prestamo/{$uuid}", "comprobante.{$ext}", 'public');
        }
        unset($data['comprobante']);

        MovimientoPrestamo::create($data);

        return redirect()->route('dashboard.prestamos.prestatarios.show', $prestatario)
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function update(UpdateMovimientoPrestamoRequest $request, Prestatario $prestatario, MovimientoPrestamo $movimiento)
    {
        abort_unless($prestatario->hogar_id === $this->hogarId(), 403);
        abort_unless($movimiento->prestatario_id === $prestatario->id, 403);

        $data = $request->validated();

        if ($request->hasFile('comprobante')) {
            if ($movimiento->comprobante_path) {
                Storage::disk('public')->deleteDirectory(dirname($movimiento->comprobante_path));
            }
            $file                    = $request->file('comprobante');
            $uuid                    = Str::uuid();
            $ext                     = $file->getClientOriginalExtension();
            $data['comprobante_path'] = $file->storeAs("movimientos_prestamo/{$uuid}", "comprobante.{$ext}", 'public');
        }
        unset($data['comprobante']);

        $movimiento->update($data);

        return redirect()->route('dashboard.prestamos.prestatarios.show', $prestatario)
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(Prestatario $prestatario, MovimientoPrestamo $movimiento)
    {
        abort_unless($prestatario->hogar_id === $this->hogarId(), 403);
        abort_unless($movimiento->prestatario_id === $prestatario->id, 403);

        if ($movimiento->comprobante_path) {
            Storage::disk('public')->deleteDirectory(dirname($movimiento->comprobante_path));
        }

        $movimiento->delete();

        return redirect()->route('dashboard.prestamos.prestatarios.show', $prestatario)
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}

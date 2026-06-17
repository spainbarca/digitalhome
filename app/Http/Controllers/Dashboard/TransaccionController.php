<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaccionRequest;
use App\Http\Requests\UpdateTransaccionRequest;
use App\Models\ProductoFinanciero;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Auth;

class TransaccionController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(StoreTransaccionRequest $request, ProductoFinanciero $productoFinanciero)
    {
        abort_unless($productoFinanciero->miembro?->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();
        $data['producto_financiero_id'] = $productoFinanciero->id;

        Transaccion::create($data);

        return redirect()->route('dashboard.productos-financieros.show', $productoFinanciero)
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function update(UpdateTransaccionRequest $request, Transaccion $transaccion)
    {
        abort_unless($transaccion->producto?->miembro?->hogar_id === $this->hogarId(), 403);

        $transaccion->update($request->validated());

        return redirect()->route('dashboard.productos-financieros.show', $transaccion->producto_financiero_id)
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(Transaccion $transaccion)
    {
        abort_unless($transaccion->producto?->miembro?->hogar_id === $this->hogarId(), 403);

        $productoId = $transaccion->producto_financiero_id;
        $transaccion->delete();

        return redirect()->route('dashboard.productos-financieros.show', $productoId)
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}

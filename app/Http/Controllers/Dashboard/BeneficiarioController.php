<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBeneficiarioRequest;
use App\Http\Requests\UpdateBeneficiarioRequest;
use App\Models\Beneficiario;
use App\Models\ProductoFinanciero;
use Illuminate\Support\Facades\Auth;

class BeneficiarioController extends Controller
{
    private function hogarId(): ?string
    {
        return Auth::user()->persona?->hogar_id;
    }

    public function store(StoreBeneficiarioRequest $request, ProductoFinanciero $productoFinanciero)
    {
        abort_unless($productoFinanciero->miembro?->hogar_id === $this->hogarId(), 403);

        $data = $request->validated();
        $data['producto_financiero_id'] = $productoFinanciero->id;

        Beneficiario::create($data);

        return redirect()->route('dashboard.productos-financieros.show', $productoFinanciero)
            ->with('success', 'Beneficiario registrado correctamente.');
    }

    public function update(UpdateBeneficiarioRequest $request, Beneficiario $beneficiario)
    {
        abort_unless($beneficiario->productoFinanciero?->miembro?->hogar_id === $this->hogarId(), 403);

        $beneficiario->update($request->validated());

        return redirect()->route('dashboard.productos-financieros.show', $beneficiario->producto_financiero_id)
            ->with('success', 'Beneficiario actualizado correctamente.');
    }

    public function destroy(Beneficiario $beneficiario)
    {
        abort_unless($beneficiario->productoFinanciero?->miembro?->hogar_id === $this->hogarId(), 403);

        $productoId = $beneficiario->producto_financiero_id;
        $beneficiario->delete();

        return redirect()->route('dashboard.productos-financieros.show', $productoId)
            ->with('success', 'Beneficiario eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UbigeoDistrito;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UbigeoController extends Controller
{
    public function buscarDistritos(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));

        $query = UbigeoDistrito::query();

        if ($q !== '') {
            $query->where(function ($sq) use ($q) {
                $sq->where('distrito', 'like', "%{$q}%")
                   ->orWhere('provincia', 'like', "%{$q}%")
                   ->orWhere('departamento', 'like', "%{$q}%");
            });
        }

        $results = $query
            ->orderBy('departamento')
            ->orderBy('provincia')
            ->orderBy('distrito')
            ->limit(30)
            ->get(['inei', 'distrito', 'provincia', 'departamento'])
            ->map(fn($d) => [
                'id'   => $d->inei,
                'text' => "{$d->distrito} — {$d->provincia} — {$d->departamento}",
            ]);

        return response()->json(['results' => $results]);
    }
}

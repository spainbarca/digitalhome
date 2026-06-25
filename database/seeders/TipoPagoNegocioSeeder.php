<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoPagoNegocioSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'tipo_pago_negocio';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'Impuesto (SUNAT)',      'icono' => 'account_balance'],
            ['nombre' => 'Arbitrios Municipales', 'icono' => 'location_city'],
            ['nombre' => 'Tasa / Derecho',        'icono' => 'request_quote'],
            ['nombre' => 'Licencia',              'icono' => 'verified'],
            ['nombre' => 'Multa',                 'icono' => 'gavel'],
            ['nombre' => 'Contribución',          'icono' => 'savings'],
            ['nombre' => 'Otro',                  'icono' => 'payments'],
        ];

        foreach ($items as $i => $item) {
            DB::table($tabla)->updateOrInsert(
                ['nombre' => $item['nombre']],
                [
                    'id'         => $ids[$item['nombre']] ?? (string) Str::uuid(),
                    'icono'      => $item['icono'],
                    'activo'     => true,
                    'orden'      => $i + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegimenTributarioSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'regimen_tributario';
        $ids = DB::table($tabla)->pluck('id', 'codigo');

        $items = [
            ['codigo' => 'NRUS', 'nombre' => 'Nuevo Régimen Único Simplificado', 'descripcion' => 'Pequeños negocios; cuota mensual fija por categoría. No permite emitir facturas.', 'icono' => 'storefront'],
            ['codigo' => 'RER',  'nombre' => 'Régimen Especial de Renta',         'descripcion' => 'Renta mensual de 1.5% de ingresos netos. Sujeto a límites de ingresos y compras.', 'icono' => 'point_of_sale'],
            ['codigo' => 'RMT',  'nombre' => 'Régimen MYPE Tributario',           'descripcion' => 'Micro y pequeña empresa; tasas escalonadas según la utilidad anual.', 'icono' => 'trending_up'],
            ['codigo' => 'RG',   'nombre' => 'Régimen General',                    'descripcion' => 'Sin límite de ingresos; pagos a cuenta y declaración jurada anual de renta.', 'icono' => 'account_balance'],
        ];

        foreach ($items as $i => $item) {
            DB::table($tabla)->updateOrInsert(
                ['codigo' => $item['codigo']],
                [
                    'id'          => $ids[$item['codigo']] ?? (string) Str::uuid(),
                    'nombre'      => $item['nombre'],
                    'descripcion' => $item['descripcion'],
                    'icono'       => $item['icono'],
                    'activo'      => true,
                    'orden'       => $i + 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}

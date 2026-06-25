<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoNegocioSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'estado_negocio';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'En Constitución',          'color' => '#3b82f6', 'icono' => 'hourglass_top'],
            ['nombre' => 'Activo',                   'color' => '#16a34a', 'icono' => 'check_circle'],
            ['nombre' => 'Suspendido Temporalmente', 'color' => '#f59e0b', 'icono' => 'pause_circle'],
            ['nombre' => 'Baja / Cese',              'color' => '#dc2626', 'icono' => 'cancel'],
        ];

        foreach ($items as $i => $item) {
            DB::table($tabla)->updateOrInsert(
                ['nombre' => $item['nombre']],
                [
                    'id'         => $ids[$item['nombre']] ?? (string) Str::uuid(),
                    'color'      => $item['color'],
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

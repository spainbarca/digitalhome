<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Pendiente',   'color' => '#F59E0B', 'icono' => 'clock'],
            ['nombre' => 'Pagado',      'color' => '#10B981', 'icono' => 'circle-check'],
            ['nombre' => 'Vencido',     'color' => '#EF4444', 'icono' => 'circle-x'],
            ['nombre' => 'En disputa',  'color' => '#6366F1', 'icono' => 'alert-circle'],
            ['nombre' => 'Anulado',     'color' => '#6B7280', 'icono' => 'ban'],
        ];

        foreach ($estados as $estado) {
            DB::table('estado_pago')->insert([
                'id'         => Str::uuid(),
                'nombre'     => $estado['nombre'],
                'color'      => $estado['color'],
                'icono'      => $estado['icono'],
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

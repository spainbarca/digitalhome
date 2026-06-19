<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoReservaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Confirmada', 'icono' => 'check_circle',     'color' => '#16A34A', 'descripcion' => 'Reserva confirmada por el proveedor.'],
            ['nombre' => 'Pendiente',  'icono' => 'hourglass_empty',  'color' => '#F59E0B', 'descripcion' => 'Reserva solicitada, a la espera de confirmación.'],
            ['nombre' => 'En espera',  'icono' => 'schedule',         'color' => '#3B82F6', 'descripcion' => 'En lista de espera o por definir.'],
            ['nombre' => 'Cancelada',  'icono' => 'cancel',           'color' => '#DC2626', 'descripcion' => 'Reserva cancelada.'],
            ['nombre' => 'Completada', 'icono' => 'task_alt',         'color' => '#6B7280', 'descripcion' => 'Servicio ya utilizado / viaje realizado.'],
        ];

        foreach ($items as $item) {
            DB::table('estado_reserva')->insert([
                'id'          => (string) Str::uuid(),
                'nombre'      => $item['nombre'],
                'icono'       => $item['icono'],
                'color'       => $item['color'],
                'descripcion' => $item['descripcion'],
                'activo'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}

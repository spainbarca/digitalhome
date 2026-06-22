<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoViajeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Planificado', 'icono' => 'event_upcoming', 'color' => '#3B82F6', 'descripcion' => 'Viaje planeado, aún no iniciado.'],
            ['nombre' => 'En curso',    'icono' => 'flight_takeoff',  'color' => '#16A34A', 'descripcion' => 'Viaje en desarrollo actualmente.'],
            ['nombre' => 'Finalizado',  'icono' => 'task_alt',        'color' => '#6B7280', 'descripcion' => 'Viaje ya realizado.'],
            ['nombre' => 'Cancelado',   'icono' => 'cancel',          'color' => '#DC2626', 'descripcion' => 'Viaje cancelado.'],
        ];

        foreach ($items as $item) {
            DB::table('estado_viaje')->insert([
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

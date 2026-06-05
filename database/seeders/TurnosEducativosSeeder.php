<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TurnosEducativosSeeder extends Seeder
{

    public function run(): void
    {
        $turnos = [
            ['nombre' => 'Mañana',   'icono' => 'wb_sunny',       'descripcion' => 'Turno de mañana'],
            ['nombre' => 'Tarde',    'icono' => 'wb_twilight',     'descripcion' => 'Turno de tarde'],
            ['nombre' => 'Noche',    'icono' => 'nightlight',      'descripcion' => 'Turno nocturno'],
            ['nombre' => 'Virtual',  'icono' => 'computer',        'descripcion' => 'Modalidad virtual o remota'],
            ['nombre' => 'Mixto',    'icono' => 'device_hub',      'descripcion' => 'Combinación de presencial y virtual'],
        ];

        foreach ($turnos as $turno) {
            DB::table('turnos_educativos')->insertOrIgnore([
                'id'          => Str::uuid(),
                'nombre'      => $turno['nombre'],
                'descripcion' => $turno['descripcion'],
                'icono'       => $turno['icono'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

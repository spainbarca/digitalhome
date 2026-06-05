<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadosMatriculaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Activa',      'icono' => 'check_circle',    'color' => 'green',  'descripcion' => 'Matrícula vigente y en curso'],
            ['nombre' => 'Culminada',   'icono' => 'task_alt',         'color' => 'blue',   'descripcion' => 'Ciclo o año académico completado'],
            ['nombre' => 'Retirada',    'icono' => 'cancel',           'color' => 'red',    'descripcion' => 'El miembro se retiró de la institución'],
            ['nombre' => 'Suspendida',  'icono' => 'pause_circle',     'color' => 'orange', 'descripcion' => 'Matrícula temporalmente suspendida'],
            ['nombre' => 'Trasladada',  'icono' => 'swap_horiz',       'color' => 'purple', 'descripcion' => 'El miembro se trasladó a otra institución'],
        ];

        foreach ($estados as $estado) {
            DB::table('estados_matricula')->insertOrIgnore([
                'id'          => Str::uuid(),
                'nombre'      => $estado['nombre'],
                'descripcion' => $estado['descripcion'],
                'icono'       => $estado['icono'],
                'color'       => $estado['color'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

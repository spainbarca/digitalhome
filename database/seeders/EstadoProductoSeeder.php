<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoProductoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Activo',    'color' => 'green',  'icono' => 'check_circle',    'descripcion' => 'Producto vigente y en uso normal.'],
            ['nombre' => 'Inactivo',  'color' => 'gray',   'icono' => 'pause_circle',    'descripcion' => 'Producto sin movimientos pero no cancelado.'],
            ['nombre' => 'Bloqueado', 'color' => 'orange', 'icono' => 'block',           'descripcion' => 'Producto bloqueado temporalmente por la entidad.'],
            ['nombre' => 'Cancelado', 'color' => 'red',    'icono' => 'cancel',          'descripcion' => 'Producto cancelado o cerrado definitivamente.'],
            ['nombre' => 'Vencido',   'color' => 'amber',  'icono' => 'schedule_send',   'descripcion' => 'Producto con fecha de vencimiento expirada.'],
        ];

        foreach ($estados as $estado) {
            DB::table('estado_producto')->insert([
                'id'          => (string) Str::uuid(),
                'nombre'      => $estado['nombre'],
                'color'       => $estado['color'],
                'icono'       => $estado['icono'],
                'descripcion' => $estado['descripcion'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

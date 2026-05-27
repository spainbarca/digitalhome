<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoVisibilidadSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'nombre'      => 'Privado',
                'descripcion' => 'Solo lo ve quien lo subió',
                'icono'       => 'lock',
            ],
            [
                'nombre'      => 'Hogar',
                'descripcion' => 'Todos los miembros del hogar pueden verlo',
                'icono'       => 'home',
            ],
            [
                'nombre'      => 'Restringido',
                'descripcion' => 'Solo usuarios con acceso explícito',
                'icono'       => 'shield',
            ],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_visibilidad')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $tipo['nombre'],
                'descripcion' => $tipo['descripcion'],
                'icono'       => $tipo['icono'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

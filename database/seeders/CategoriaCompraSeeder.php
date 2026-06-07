<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaCompraSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Supermercado',     'icono' => 'shopping_cart'],
            ['nombre' => 'Electrodoméstico', 'icono' => 'kitchen'],
            ['nombre' => 'Tecnología',       'icono' => 'devices'],
            ['nombre' => 'Mueble',           'icono' => 'weekend'],
            ['nombre' => 'Ropa',             'icono' => 'checkroom'],
            ['nombre' => 'Ferretería',       'icono' => 'hardware'],
            ['nombre' => 'Hogar',            'icono' => 'home'],
            ['nombre' => 'Farmacia',         'icono' => 'local_pharmacy'],
            ['nombre' => 'Otro',             'icono' => 'more_horiz'],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias_compra')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $categoria['nombre'],
                'icono'       => $categoria['icono'],
                'imagen_path' => null,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

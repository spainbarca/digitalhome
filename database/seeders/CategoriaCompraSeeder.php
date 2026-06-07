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
            ['nombre' => 'Supermercado',          'icono' => 'shopping_cart'],
            ['nombre' => 'Alimentos y bebidas',   'icono' => 'restaurant'],
            ['nombre' => 'Electrodoméstico',      'icono' => 'kitchen'],
            ['nombre' => 'Tecnología',            'icono' => 'devices'],
            ['nombre' => 'Mueble',                'icono' => 'weekend'],
            ['nombre' => 'Hogar y decoración',    'icono' => 'home'],
            ['nombre' => 'Ropa y calzado',        'icono' => 'checkroom'],
            ['nombre' => 'Ferretería',            'icono' => 'hardware'],
            ['nombre' => 'Farmacia y salud',      'icono' => 'local_pharmacy'],
            ['nombre' => 'Cuidado personal',      'icono' => 'health_and_beauty'],
            ['nombre' => 'Limpieza',              'icono' => 'cleaning_services'],
            ['nombre' => 'Bebé',                  'icono' => 'child_friendly'],
            ['nombre' => 'Mascotas',              'icono' => 'pets'],
            ['nombre' => 'Útiles escolares',      'icono' => 'school'],
            ['nombre' => 'Librería y papelería',  'icono' => 'menu_book'],
            ['nombre' => 'Deportes',              'icono' => 'fitness_center'],
            ['nombre' => 'Juguetes',              'icono' => 'toys'],
            ['nombre' => 'Combustible',           'icono' => 'local_gas_station'],
            ['nombre' => 'Transporte',            'icono' => 'directions_car'],
            ['nombre' => 'Herramientas',          'icono' => 'build'],
            ['nombre' => 'Jardinería',            'icono' => 'yard'],
            ['nombre' => 'Regalos',               'icono' => 'card_giftcard'],
            ['nombre' => 'Electrónica menor',     'icono' => 'devices_other'],
            ['nombre' => 'Otro',                  'icono' => 'more_horiz'],
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

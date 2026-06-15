<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaConceptoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categorias = [
            ['nombre' => 'Dinero / Efectivo',  'descripcion' => 'Préstamos en efectivo, transferencias y yapeos.',          'icono' => 'payments'],
            ['nombre' => 'Comida y Bebida',    'descripcion' => 'Almuerzos, cenas, bebidas u otras invitaciones de comida.', 'icono' => 'restaurant'],
            ['nombre' => 'Transporte',         'descripcion' => 'Pasajes, gasolina, taxi compartido u otros traslados.',     'icono' => 'directions_car'],
            ['nombre' => 'Productos',          'descripcion' => 'Artículos físicos prestados o comprados para otro.',        'icono' => 'shopping_bag'],
            ['nombre' => 'Servicios',          'descripcion' => 'Pagos de servicios cubiertos por cuenta de otro.',          'icono' => 'receipt_long'],
            ['nombre' => 'Otros',              'descripcion' => 'Cualquier concepto que no encaje en las categorías anteriores.', 'icono' => 'more_horiz'],
        ];

        foreach ($categorias as $cat) {
            DB::table('categorias_concepto')->insert([
                'id'          => (string) Str::uuid(),
                'nombre'      => $cat['nombre'],
                'descripcion' => $cat['descripcion'],
                'icono'       => $cat['icono'],
                'activo'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
                'deleted_at'  => null,
            ]);
        }
    }
}

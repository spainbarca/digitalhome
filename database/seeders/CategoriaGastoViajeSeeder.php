<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaGastoViajeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Transporte',   'icono' => 'commute',      'color' => '#2563EB', 'descripcion' => 'Pasajes, taxis, combustible, peajes.'],
            ['nombre' => 'Alojamiento',  'icono' => 'hotel',        'color' => '#4F46E5', 'descripcion' => 'Hospedaje y estadía.'],
            ['nombre' => 'Alimentación', 'icono' => 'restaurant',   'color' => '#F59E0B', 'descripcion' => 'Comidas, bebidas, snacks.'],
            ['nombre' => 'Actividades',  'icono' => 'local_activity','color' => '#16A34A', 'descripcion' => 'Tours, entradas, excursiones.'],
            ['nombre' => 'Compras',      'icono' => 'shopping_bag', 'color' => '#EC4899', 'descripcion' => 'Souvenirs, artesanías, compras varias.'],
            ['nombre' => 'Propinas',     'icono' => 'volunteer_activism', 'color' => '#14B8A6', 'descripcion' => 'Propinas y gastos menores en efectivo.'],
            ['nombre' => 'Otros',        'icono' => 'more_horiz',   'color' => '#6B7280', 'descripcion' => 'Cualquier otro gasto del viaje.'],
        ];

        foreach ($items as $item) {
            DB::table('categoria_gasto_viaje')->insert([
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

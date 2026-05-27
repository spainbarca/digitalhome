<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Servicios Básicos', 'icono' => 'zap',          'color' => '#F59E0B'],
            ['nombre' => 'Médico',            'icono' => 'heart-pulse',   'color' => '#EF4444'],
            ['nombre' => 'Académico',         'icono' => 'graduation-cap','color' => '#8B5CF6'],
            ['nombre' => 'Laboral',           'icono' => 'briefcase',     'color' => '#3B82F6'],
            ['nombre' => 'Compras',           'icono' => 'shopping-bag',  'color' => '#10B981'],
            ['nombre' => 'Legal',             'icono' => 'scale',         'color' => '#6B7280'],
            ['nombre' => 'Impuestos',         'icono' => 'landmark',      'color' => '#F97316'],
            ['nombre' => 'Propiedad',         'icono' => 'home',          'color' => '#0EA5E9'],
            ['nombre' => 'Seguro',            'icono' => 'shield',        'color' => '#14B8A6'],
            ['nombre' => 'Bancario',          'icono' => 'credit-card',   'color' => '#6366F1'],
        ];

        foreach ($categorias as $cat) {
            DB::table('categorias')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $cat['nombre'],
                'icono'       => $cat['icono'],
                'color'       => $cat['color'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

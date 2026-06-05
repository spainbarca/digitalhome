<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NivelesEducativosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveles = [
            ['orden' => 1, 'nombre' => 'Estimulación Temprana', 'icono' => 'child_care',          'descripcion' => 'Para niños de 0 a 2 años'],
            ['orden' => 2, 'nombre' => 'Inicial',               'icono' => 'sentiment_very_satisfied', 'descripcion' => 'Educación inicial de 3 a 5 años'],
            ['orden' => 3, 'nombre' => 'Primaria',              'icono' => 'menu_book',            'descripcion' => 'Educación primaria, 1° a 6° grado'],
            ['orden' => 4, 'nombre' => 'Secundaria',            'icono' => 'import_contacts',      'descripcion' => 'Educación secundaria, 1° a 5° grado'],
            ['orden' => 5, 'nombre' => 'Técnico Superior',      'icono' => 'engineering',          'descripcion' => 'Instituto técnico o vocacional'],
            ['orden' => 6, 'nombre' => 'Universidad',           'icono' => 'account_balance',      'descripcion' => 'Educación superior universitaria'],
            ['orden' => 7, 'nombre' => 'Postgrado',             'icono' => 'workspace_premium',    'descripcion' => 'Maestría, doctorado o especialización'],
            ['orden' => 8, 'nombre' => 'Curso / Taller',        'icono' => 'category',             'descripcion' => 'Cursos cortos, talleres o certificaciones'],
            ['orden' => 9, 'nombre' => 'Idiomas',               'icono' => 'translate',            'descripcion' => 'Cursos de idiomas y lenguas extranjeras'],
        ];

        foreach ($niveles as $nivel) {
            DB::table('niveles_educativos')->insertOrIgnore([
                'id'          => Str::uuid(),
                'nombre'      => $nivel['nombre'],
                'descripcion' => $nivel['descripcion'],
                'icono'       => $nivel['icono'],
                'orden'       => $nivel['orden'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

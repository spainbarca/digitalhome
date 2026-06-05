<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoInstitucionEducativaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Colegio Público',        'icono' => 'school',            'descripcion' => 'Institución educativa estatal de nivel básico'],
            ['nombre' => 'Colegio Privado',        'icono' => 'school',            'descripcion' => 'Institución educativa privada de nivel básico'],
            ['nombre' => 'Instituto Técnico',      'icono' => 'engineering',       'descripcion' => 'Educación técnica y vocacional'],
            ['nombre' => 'Universidad',            'icono' => 'account_balance',   'descripcion' => 'Educación superior universitaria'],
            ['nombre' => 'Academia',               'icono' => 'menu_book',         'descripcion' => 'Centro de preparación preuniversitaria o refuerzo'],
            ['nombre' => 'Centro de Idiomas',      'icono' => 'translate',         'descripcion' => 'Cursos de idiomas y lenguas extranjeras'],
            ['nombre' => 'Taller',                 'icono' => 'construction',      'descripcion' => 'Talleres de habilidades manuales o artísticas'],
            ['nombre' => 'Curso Online',           'icono' => 'computer',          'descripcion' => 'Plataformas y cursos de educación virtual'],
            ['nombre' => 'Postgrado',              'icono' => 'workspace_premium', 'descripcion' => 'Maestrías, doctorados y especializaciones'],
            ['nombre' => 'Centro de Estimulación', 'icono' => 'child_care',        'descripcion' => 'Estimulación temprana para primera infancia'],
            ['nombre' => 'Escuela de Arte',        'icono' => 'palette',           'descripcion' => 'Música, danza, teatro, artes visuales'],
            ['nombre' => 'Centro Deportivo',       'icono' => 'sports',            'descripcion' => 'Escuelas y academias deportivas'],
            ['nombre' => 'Otro',                   'icono' => 'category',          'descripcion' => 'Otro tipo de institución educativa'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_institucion_educativa')->insertOrIgnore([
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

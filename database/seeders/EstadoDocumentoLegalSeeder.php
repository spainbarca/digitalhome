<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoDocumentoLegalSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Vigente',     'descripcion' => 'Documento en plena validez',                              'icono' => 'check_circle',    'color' => 'green'],
            ['nombre' => 'Por vencer',  'descripcion' => 'Próximo a vencer en menos de 30 días',                   'icono' => 'schedule',         'color' => 'orange'],
            ['nombre' => 'Vencido',     'descripcion' => 'Documento con fecha de vencimiento superada',            'icono' => 'cancel',           'color' => 'red'],
            ['nombre' => 'En trámite',  'descripcion' => 'Documento en proceso de obtención o renovación',         'icono' => 'hourglass_empty',  'color' => 'blue'],
            ['nombre' => 'Archivado',   'descripcion' => 'Documento guardado sin vigencia activa',                 'icono' => 'archive',          'color' => 'gray'],
        ];

        foreach ($estados as $estado) {
            DB::table('estado_documento_legal')->insertOrIgnore([
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

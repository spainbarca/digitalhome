<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoServicioSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Recibo',      'icono' => 'file-text'],
            ['nombre' => 'Contrato',    'icono' => 'file-signature'],
            ['nombre' => 'Carta',       'icono' => 'mail'],
            ['nombre' => 'Constancia',  'icono' => 'badge-check'],
            ['nombre' => 'Otro',        'icono' => 'file'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_documento_servicio')->insert([
                'id'         => Str::uuid(),
                'nombre'     => $tipo['nombre'],
                'icono'      => $tipo['icono'],
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

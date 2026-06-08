<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoEntidadLegalSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Notaría',                 'descripcion' => 'Oficina notarial pública',                                                          'icono' => 'gavel'],
            ['nombre' => 'Estudio jurídico',        'descripcion' => 'Estudio de abogados privado',                                                       'icono' => 'balance'],
            ['nombre' => 'SUNARP',                  'descripcion' => 'Superintendencia Nacional de Registros Públicos',                                   'icono' => 'domain'],
            ['nombre' => 'RENIEC',                  'descripcion' => 'Registro Nacional de Identificación y Estado Civil',                                'icono' => 'badge'],
            ['nombre' => 'Municipalidad',           'descripcion' => 'Gobierno local municipal',                                                          'icono' => 'account_balance'],
            ['nombre' => 'SUNAT',                   'descripcion' => 'Superintendencia Nacional de Aduanas y Administración Tributaria',                  'icono' => 'receipt'],
            ['nombre' => 'Juzgado',                 'descripcion' => 'Órgano jurisdiccional del poder judicial',                                          'icono' => 'account_balance'],
            ['nombre' => 'PNP',                     'descripcion' => 'Policía Nacional del Perú',                                                         'icono' => 'local_police'],
            ['nombre' => 'Otra',                    'descripcion' => 'Entidad legal no categorizada',                                                     'icono' => 'business'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_entidad_legal')->insertOrIgnore([
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

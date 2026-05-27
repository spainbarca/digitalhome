<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = [
            ['nombre' => 'Yape',                'icono' => 'smartphone'],
            ['nombre' => 'Plin',                'icono' => 'smartphone'],
            ['nombre' => 'BCP',                 'icono' => 'building'],
            ['nombre' => 'Interbank',           'icono' => 'building'],
            ['nombre' => 'BBVA',                'icono' => 'building'],
            ['nombre' => 'Scotiabank',          'icono' => 'building'],
            ['nombre' => 'Ventanilla',          'icono' => 'landmark'],
            ['nombre' => 'Débito automático',   'icono' => 'repeat'],
            ['nombre' => 'Tarjeta crédito',     'icono' => 'credit-card'],
            ['nombre' => 'Tarjeta débito',      'icono' => 'credit-card'],
            ['nombre' => 'Efectivo',            'icono' => 'banknote'],
            ['nombre' => 'Otro',                'icono' => 'circle-ellipsis'],
        ];

        foreach ($metodos as $metodo) {
            DB::table('metodo_pago')->insert([
                'id'         => Str::uuid(),
                'nombre'     => $metodo['nombre'],
                'icono'      => $metodo['icono'],
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

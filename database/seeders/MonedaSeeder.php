<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonedaSeeder extends Seeder
{
    public function run()
    {
        DB::table('monedas')->insert([
            [
                'nombre' => 'Sol peruano',
                'codigo' => 'PEN',
                'simbolo' => 'S/',
                'tasa_cambio' => 1.000000,
                'moneda_local' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Dólar estadounidense',
                'codigo' => 'USD',
                'simbolo' => '$',
                'tasa_cambio' => 3.750000,
                'moneda_local' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Euro',
                'codigo' => 'EUR',
                'simbolo' => '€',
                'tasa_cambio' => 4.080000,
                'moneda_local' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

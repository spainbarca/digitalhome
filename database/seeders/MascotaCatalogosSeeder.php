<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MascotaCatalogosSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EspecieSeeder::class,
            RazaSeeder::class,
            TipoEventoSanitarioSeeder::class,
            TipoDocumentoMascotaSeeder::class,
            EstadoMascotaSeeder::class,
            SexoMascotaSeeder::class,
            OrigenMascotaSeeder::class,
        ]);
    }
}

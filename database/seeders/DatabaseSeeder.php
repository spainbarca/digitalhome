<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoDocumentoSeeder::class,
            PaisesSeeder::class,
            UbigeoDepartamentosSeeder::class,
            UbigeoProvinciasSeeder::class,
            UbigeoDistritosSeeder::class,
            CiudadSeeder::class,
            UnidadMedidaSeeder::class,
            TipoServicioSeeder::class,
            TipoContribuyenteSeeder::class,
            SectorSeeder::class,
        ]);
    }
}

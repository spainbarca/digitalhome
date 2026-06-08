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
            CategoriaSeeder::class,
            EstadoPagoSeeder::class,
            MetodoPagoSeeder::class,
            MonedaSeeder::class,
            ParentescoSeeder::class,
            TipoArchivoSeeder::class,
            TipoDocumentoServicioSeeder::class,
            TipoInmuebleSeeder::class,
            TipoVisibilidadSeeder::class,
            EspecialidadesMedicasSeeder::class,
            TiposCentroMedicoSeeder::class,
            TipoDocumentoMedicoSeeder::class,
            TipoInstitucionEducativaSeeder::class,
            TipoDocumentoEducativoSeeder::class,
            NivelesEducativosSeeder::class,
            TurnosEducativosSeeder::class,
            EstadosMatriculaSeeder::class,
            TipoDocumentoLegalSeeder::class,
            TipoEntidadLegalSeeder::class,
            EstadoDocumentoLegalSeeder::class,
        ]);
    }
}

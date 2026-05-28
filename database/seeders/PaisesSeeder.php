<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PaisesSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/paises.txt');

        if (!File::exists($path)) {
            $this->command->error("No existe el archivo: {$path}");
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!$lines || count($lines) < 2) {
            $this->command->error('El archivo no tiene datos válidos.');
            return;
        }

        // Quita encabezado
        array_shift($lines);

        $data = [];

        foreach ($lines as $line) {
            $row = explode("\t", $line);

            if (count($row) < 9) {
                continue;
            }

            $data[] = [
                'id_pais'        => (int) trim($row[0]),
                'iso2'           => trim($row[1]) ?: null,
                'nombre'         => html_entity_decode(trim($row[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'nombre_oficial' => html_entity_decode(trim($row[3]), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'iso3'           => trim($row[4]) ?: null,
                'codigo_num'     => trim($row[5]) ?: null,
                'miembro_onu'    => trim($row[6]) ?: null,
                'codigo_tel'     => trim($row[7]) ?: null,
                'dominio'        => trim($row[8]) ?: null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        if (empty($data)) {
            $this->command->warn('No hay datos para insertar.');
            return;
        }

        // IMPORTANTE:
        // NO usar truncate ni delete porque hay FK desde entidad_sedes
        // Usamos UPSERT para mantener integridad referencial

        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('paises')->upsert(
                $chunk,
                ['iso2'], // clave única
                [
                    'id_pais',
                    'nombre',
                    'nombre_oficial',
                    'iso3',
                    'codigo_num',
                    'miembro_onu',
                    'codigo_tel',
                    'dominio',
                    'updated_at',
                ]
            );
        }

        $this->command->info('Seeder de países ejecutado correctamente.');
    }
}

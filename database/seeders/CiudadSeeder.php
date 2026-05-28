<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciudad;

class CiudadSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/ciudades_base.csv');

        if (!file_exists($path)) {
            $this->command->error("No existe el archivo: {$path}");
            return;
        }

        $file = fopen($path, 'r');

        // Saltar cabecera
        fgetcsv($file, 0, ',');

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $paisIso2 = trim($row[0] ?? '');
            $region = trim($row[1] ?? '');
            $nombre = trim($row[2] ?? '');
            $estado = (int) ($row[3] ?? 1);

            if ($paisIso2 === '' || $nombre === '') {
                continue;
            }

            Ciudad::updateOrCreate(
                [
                    'pais_iso2' => $paisIso2,
                    'region' => $region,
                    'nombre' => $nombre,
                ],
                [
                    'estado' => $estado,
                ]
            );
        }

        fclose($file);
    }
}
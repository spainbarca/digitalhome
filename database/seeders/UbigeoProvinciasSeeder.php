<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeoProvinciasSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/ubigeo_provincia.csv');

        if (!file_exists($path)) {
            $this->command->error("No existe: {$path}");
            return;
        }

        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        DB::table('ubigeo_provincias')->delete();

        $data = [];

        foreach ($rows as $row) {
            if (count($row) !== count($header)) {
                continue;
            }

            $item = array_combine($header, $row);
            $inei = trim($item['inei']);
            $departamentoInei = substr($inei, 0, 2) . '0000';

            $data[] = [
                'inei' => $inei,
                'reniec' => trim($item['reniec']) ?: null,
                'departamento_inei' => $departamentoInei,
                'departamento' => trim($item['departamento']),
                'provincia' => trim($item['provincia']),
                'region' => trim($item['region']) ?: null,
                'macroregion_inei' => trim($item['macroregion_inei']) ?: null,
                'macroregion_minsa' => trim($item['macroregion_minsa']) ?: null,
                'iso_3166_2' => trim($item['iso_3166_2']) ?: null,
                'fips' => trim($item['fips']) ?: null,
                'capital' => trim($item['capital']) ?: null,
                'superficie' => $this->toDecimalOrNull($item['superficie']),
                'pob_densidad_2020' => $this->toDecimalOrNull($item['pob_densidad_2020']),
                'altitude' => is_numeric(trim((string) $item['altitude'])) ? (int) trim($item['altitude']) : null,
                'latitude' => $this->toDecimalOrNull($item['latitude']),
                'longitude' => $this->toDecimalOrNull($item['longitude']),
                'indice_densidad_estado' => $this->toDecimalOrNull($item['indice_densidad_estado']),
                'indice_vulnerabilidad_alimentaria' => $this->toDecimalOrNull($item['indice_vulnerabilidad_alimentaria']),
                'idh_2019' => $this->toDecimalOrNull($item['idh_2019']),
                'pct_pobreza_total' => $this->toDecimalOrNull($item['pct_pobreza_total']),
                'pct_pobreza_extrema' => $this->toDecimalOrNull($item['pct_pobreza_extrema']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('ubigeo_provincias')->insert($chunk);
        }
    }

    private function toDecimalOrNull($value)
    {
        $value = trim((string) $value);
        return is_numeric($value) ? $value : null;
    }
}

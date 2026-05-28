<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeoDistritosSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/ubigeo_distrito.csv');

        if (!file_exists($path)) {
            $this->command->error("No existe: {$path}");
            return;
        }

        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        DB::table('ubigeo_distritos')->delete();

        $departamentos = DB::table('ubigeo_departamentos')->pluck('inei')->toArray();
        $provincias = DB::table('ubigeo_provincias')->pluck('inei')->toArray();

        foreach ($rows as $row) {
            if (count($row) !== count($header)) {
                continue;
            }

            $item = array_combine($header, $row);
            $inei = trim($item['inei']);

            $departamentoInei = substr($inei, 0, 2) . '0000';
            $provinciaInei = substr($inei, 0, 4) . '00';

            if (!in_array($departamentoInei, $departamentos) || !in_array($provinciaInei, $provincias)) {
                $this->command->warn("Saltando distrito {$inei}: no existe departamento/provincia padre");
                continue;
            }

            $data = [
                'inei' => $inei,
                'reniec' => trim($item['reniec']) ?: null,
                'departamento_inei' => $departamentoInei,
                'provincia_inei' => $provinciaInei,
                'departamento' => trim($item['departamento']),
                'provincia' => trim($item['provincia']),
                'distrito' => trim($item['distrito']),
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
                'indice_vulnerabilidad_alimentaria' => $this->toDecimalOrNull($item['indice_vulnerabilidad_alimentaria']),
                'idh_2019' => $this->toDecimalOrNull($item['idh_2019']),
                'pct_pobreza_total' => $this->toDecimalOrNull($item['pct_pobreza_total']),
                'pct_pobreza_extrema' => $this->toDecimalOrNull($item['pct_pobreza_extrema']),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            try {
                DB::table('ubigeo_distritos')->insert($data);
            } catch (\Exception $e) {
                $this->command->warn("Error insertando distrito {$inei}: " . $e->getMessage());
                continue;
            }
        }
    }

    private function toDecimalOrNull($value)
    {
        $value = trim((string) $value);
        return is_numeric($value) ? $value : null;
    }
}

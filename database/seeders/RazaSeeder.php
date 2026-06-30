<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RazaSeeder extends Seeder
{
    public function run(): void
    {
        $especies = DB::table('especies')->pluck('id', 'nombre');

        // Lookup compuesto "especie_id|nombre" → id para preservar UUIDs en re-runs.
        $existentes = DB::table('razas')
            ->select('id', 'especie_id', 'nombre')
            ->get()
            ->keyBy(fn($r) => $r->especie_id . '|' . $r->nombre);

        $data = [
            'Perro' => [
                'Mestizo',
                'Labrador Retriever',
                'Golden Retriever',
                'Bulldog Francés',
                'Poodle (Caniche)',
                'Schnauzer',
                'Shih Tzu',
                'Pug',
                'Chihuahua',
                'Pastor Alemán',
                'Boxer',
                'Beagle',
                'Cocker Spaniel',
                'Yorkshire Terrier',
                'Pitbull',
                'Husky Siberiano',
                'Dálmata',
            ],
            'Gato' => [
                'Mestizo (Común Europeo)',
                'Siamés',
                'Persa',
                'Angora',
                'Maine Coon',
                'Bengala',
                'Británico de Pelo Corto',
                'Esfinge (Sphynx)',
                'Ragdoll',
                'Azul Ruso',
            ],
        ];

        foreach ($data as $especieNombre => $razas) {
            $especieId = $especies[$especieNombre] ?? null;

            if (! $especieId) {
                continue;
            }

            foreach ($razas as $razaNombre) {
                $key = $especieId . '|' . $razaNombre;

                DB::table('razas')->updateOrInsert(
                    ['especie_id' => $especieId, 'nombre' => $razaNombre],
                    [
                        'id'         => $existentes[$key]->id ?? (string) Str::uuid(),
                        'icono'      => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}

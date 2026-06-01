<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EspecialidadesMedicasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            ['nombre' => 'Medicina General',        'icono' => 'medical_services'],
            ['nombre' => 'Cardiología',              'icono' => 'favorite'],
            ['nombre' => 'Dermatología',             'icono' => 'dermatology'],
            ['nombre' => 'Endocrinología',           'icono' => 'biotech'],
            ['nombre' => 'Gastroenterología',        'icono' => 'gastroenterology'],
            ['nombre' => 'Ginecología',              'icono' => 'pregnant_woman'],
            ['nombre' => 'Neurología',               'icono' => 'neurology'],
            ['nombre' => 'Odontología',              'icono' => 'dentistry'],
            ['nombre' => 'Oftalmología',             'icono' => 'visibility'],
            ['nombre' => 'Oncología',                'icono' => 'oncology'],
            ['nombre' => 'Ortopedia y Traumatología','icono' => 'orthopedics'],
            ['nombre' => 'Otorrinolaringología',     'icono' => 'hearing'],
            ['nombre' => 'Pediatría',                'icono' => 'child_care'],
            ['nombre' => 'Psicología',               'icono' => 'psychology'],
            ['nombre' => 'Psiquiatría',              'icono' => 'psychiatry'],
            ['nombre' => 'Neumología',               'icono' => 'pulmonology'],
            ['nombre' => 'Reumatología',             'icono' => 'rheumatology'],
            ['nombre' => 'Urología',                 'icono' => 'urology'],
            ['nombre' => 'Nutrición y Dietética',    'icono' => 'nutrition'],
            ['nombre' => 'Fisioterapia',             'icono' => 'physical_therapy'],
            ['nombre' => 'Radiología',               'icono' => 'radiology'],
            ['nombre' => 'Anestesiología',           'icono' => 'anesthesiology'],
            ['nombre' => 'Cirugía General',          'icono' => 'surgery'],
            ['nombre' => 'Medicina de Emergencias',  'icono' => 'emergency'],
            ['nombre' => 'Geriatría',                'icono' => 'elderly'],
        ];

        foreach ($especialidades as $especialidad) {
            DB::table('especialidades_medicas')->insertOrIgnore([
                'id'         => Str::uuid(),
                'nombre'     => $especialidad['nombre'],
                'icono'      => $especialidad['icono'],
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

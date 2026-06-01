<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoMedicoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Receta Médica',              'icono' => 'medication',        'descripcion' => 'Prescripción de medicamentos emitida por un médico'],
            ['nombre' => 'Resultado de Laboratorio',   'icono' => 'biotech',           'descripcion' => 'Análisis de sangre, orina u otras muestras biológicas'],
            ['nombre' => 'Informe Médico',             'icono' => 'description',       'descripcion' => 'Informe o reporte emitido por un médico o especialista'],
            ['nombre' => 'Radiografía',                'icono' => 'radiology',         'descripcion' => 'Imagen diagnóstica por rayos X'],
            ['nombre' => 'Ecografía',                  'icono' => 'ultrasound',        'descripcion' => 'Imagen diagnóstica por ultrasonido'],
            ['nombre' => 'Tomografía',                 'icono' => 'radiology',         'descripcion' => 'Imagen diagnóstica por tomografía computarizada'],
            ['nombre' => 'Resonancia Magnética',       'icono' => 'mri_scan',          'descripcion' => 'Imagen diagnóstica por resonancia magnética'],
            ['nombre' => 'Certificado Médico',         'icono' => 'verified',          'descripcion' => 'Documento oficial que certifica el estado de salud'],
            ['nombre' => 'Historia Clínica',           'icono' => 'folder_shared',     'descripcion' => 'Registro completo del historial médico del paciente'],
            ['nombre' => 'Carnet de Vacunación',       'icono' => 'vaccines',          'descripcion' => 'Registro de vacunas aplicadas'],
            ['nombre' => 'Consentimiento Informado',   'icono' => 'how_to_reg',        'descripcion' => 'Documento de autorización para procedimientos médicos'],
            ['nombre' => 'Epicrisis',                  'icono' => 'summarize',         'descripcion' => 'Resumen de hospitalización o atención'],
            ['nombre' => 'Orden de Examen',            'icono' => 'assignment',        'descripcion' => 'Solicitud médica para realización de exámenes'],
            ['nombre' => 'Presupuesto Médico',         'icono' => 'request_quote',     'descripcion' => 'Cotización de procedimientos o tratamientos médicos'],
            ['nombre' => 'Otro',                       'icono' => 'attach_file',       'descripcion' => 'Documento médico no clasificado en las categorías anteriores'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_documento_medico')->insertOrIgnore([
                'id'          => Str::uuid(),
                'nombre'      => $tipo['nombre'],
                'icono'       => $tipo['icono'],
                'descripcion' => $tipo['descripcion'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

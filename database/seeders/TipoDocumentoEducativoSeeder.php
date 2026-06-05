<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoEducativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Libreta de Notas',        'icono' => 'grade',              'descripcion' => 'Reporte de calificaciones del período académico'],
            ['nombre' => 'Certificado de Estudios', 'icono' => 'workspace_premium',  'descripcion' => 'Certificado oficial de estudios cursados'],
            ['nombre' => 'Diploma',                 'icono' => 'military_tech',      'descripcion' => 'Diploma de conclusión o mérito académico'],
            ['nombre' => 'Constancia de Matrícula', 'icono' => 'verified',           'descripcion' => 'Constancia oficial de matrícula vigente'],
            ['nombre' => 'Boleta de Pago',          'icono' => 'receipt',            'descripcion' => 'Comprobante de pago de pensión o matrícula'],
            ['nombre' => 'Contrato de Matrícula',   'icono' => 'description',        'descripcion' => 'Contrato firmado entre institución y apoderado'],
            ['nombre' => 'Syllabus / Malla',        'icono' => 'list_alt',           'descripcion' => 'Plan de estudios o malla curricular'],
            ['nombre' => 'Certificado de Idioma',   'icono' => 'translate',          'descripcion' => 'Certificación de nivel en idioma extranjero'],
            ['nombre' => 'Certificado Online',      'icono' => 'computer',           'descripcion' => 'Certificado de curso virtual o plataforma online'],
            ['nombre' => 'Informe de Conducta',     'icono' => 'assignment_ind',     'descripcion' => 'Reporte de comportamiento o disciplina'],
            ['nombre' => 'Resolución de Traslado',  'icono' => 'swap_horiz',         'descripcion' => 'Documento de traslado entre instituciones'],
            ['nombre' => 'Carnet Escolar',          'icono' => 'badge',              'descripcion' => 'Carnet de identificación estudiantil'],
            ['nombre' => 'Otro',                    'icono' => 'attach_file',        'descripcion' => 'Documento educativo no clasificado'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_documento_educativo')->insertOrIgnore([
                'id'          => Str::uuid(),
                'nombre'      => $tipo['nombre'],
                'descripcion' => $tipo['descripcion'],
                'icono'       => $tipo['icono'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoArchivoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            // nombre            extension  mime_type                                           icono          permitido  max_bytes (5MB, 10MB...)
            ['PDF',              'pdf',     'application/pdf',                                  'file-text',   true,  10485760],
            ['Imagen JPEG',      'jpg',     'image/jpeg',                                       'image',       true,   5242880],
            ['Imagen JPEG',      'jpeg',    'image/jpeg',                                       'image',       true,   5242880],
            ['Imagen PNG',       'png',     'image/png',                                        'image',       true,   5242880],
            ['Imagen WebP',      'webp',    'image/webp',                                       'image',       true,   5242880],
            ['Word',             'docx',    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'file', true, 10485760],
            ['Excel',            'xlsx',    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       'table', true, 10485760],
            ['Texto plano',      'txt',     'text/plain',                                       'file',        true,   1048576],
            // No permitidos (registrados para poder rechazarlos con mensaje claro)
            ['Ejecutable',       'exe',     'application/octet-stream',                         'ban',         false,         0],
            ['Script',           'sh',      'application/x-sh',                                 'ban',         false,         0],
        ];

        foreach ($tipos as [$nombre, $extension, $mime, $icono, $permitido, $max]) {
            DB::table('tipo_archivo')->insert([
                'id'                => Str::uuid(),
                'nombre'            => $nombre,
                'extension'         => $extension,
                'mime_type'         => $mime,
                'icono'             => $icono,
                'permitido'         => $permitido,
                'tamano_max_bytes'  => $max,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}

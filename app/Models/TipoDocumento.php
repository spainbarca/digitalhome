<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipos_documento';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'longitud',
        'es_numerico',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'longitud'    => 'integer',
            'es_numerico' => 'boolean',
            'estado'      => 'boolean',
        ];
    }
}

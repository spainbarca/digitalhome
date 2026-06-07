<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoComercio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_comercio';

    protected $fillable = [
        'nombre',
        'icono',
        'activo',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaCompra extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'categorias_compra';

    protected $fillable = [
        'nombre',
        'icono',
        'activo',
        'descripcion',
        'imagen_path',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}

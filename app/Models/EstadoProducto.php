<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoProducto extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'estado_producto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'icono',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function productosFinancieros(): HasMany
    {
        return $this->hasMany(ProductoFinanciero::class, 'estado_producto_id');
    }
}

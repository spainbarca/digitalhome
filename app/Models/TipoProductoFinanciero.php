<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoProductoFinanciero extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_producto_financiero';

    protected $fillable = [
        'nombre',
        'descripcion',
        'naturaleza',
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
        return $this->hasMany(ProductoFinanciero::class, 'tipo_producto_financiero_id');
    }
}

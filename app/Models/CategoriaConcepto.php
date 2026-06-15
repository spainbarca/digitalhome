<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaConcepto extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'categorias_concepto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function conceptosPago(): HasMany
    {
        return $this->hasMany(ConceptoPago::class, 'categoria_concepto_id');
    }
}

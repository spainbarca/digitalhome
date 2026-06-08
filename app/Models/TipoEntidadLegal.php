<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEntidadLegal extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_entidad_legal';

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

    public function entidadesLegales(): HasMany
    {
        return $this->hasMany(EntidadLegal::class, 'tipo_entidad_legal_id');
    }
}

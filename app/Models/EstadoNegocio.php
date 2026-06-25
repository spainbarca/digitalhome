<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoNegocio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'estado_negocio';

    protected $fillable = [
        'nombre',
        'color',
        'icono',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function negocios(): HasMany
    {
        return $this->hasMany(Negocio::class, 'estado_negocio_id');
    }
}

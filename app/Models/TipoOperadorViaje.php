<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoOperadorViaje extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_operador_viaje';

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

    public function operadoresViaje(): HasMany
    {
        return $this->hasMany(OperadorViaje::class, 'tipo_operador_viaje_id');
    }
}

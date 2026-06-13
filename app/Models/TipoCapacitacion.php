<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoCapacitacion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_capacitacion';

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

    public function capacitaciones(): HasMany
    {
        return $this->hasMany(Capacitacion::class, 'tipo_capacitacion_id');
    }
}

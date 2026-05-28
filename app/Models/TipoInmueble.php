<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoInmueble extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_inmueble';

    protected $fillable = [
        'nombre',
        'icono',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function propiedadesInmueble(): HasMany
    {
        return $this->hasMany(PropiedadInmueble::class, 'tipo_inmueble_id');
    }
}

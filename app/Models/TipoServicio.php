<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoServicio extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_servicio';

    protected $fillable = [
        'nombre',
        'icono',
        'unidad_medida_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function proveedoresServicio(): HasMany
    {
        return $this->hasMany(ProveedorServicio::class, 'tipo_servicio_id');
    }
}

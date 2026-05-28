<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedida extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'unidad_medida';

    protected $fillable = [
        'nombre',
        'simbolo',
        'icono',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function tiposServicio(): HasMany
    {
        return $this->hasMany(TipoServicio::class, 'unidad_medida_id');
    }
}

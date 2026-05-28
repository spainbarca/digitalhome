<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoPago extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'estado_pago';

    protected $fillable = [
        'nombre',
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

    public function documentosServicio(): HasMany
    {
        return $this->hasMany(DocumentoServicio::class, 'estado_pago_id');
    }
}

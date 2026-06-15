<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoTransaccion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_transaccion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'naturaleza',
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

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class, 'tipo_transaccion_id');
    }
}

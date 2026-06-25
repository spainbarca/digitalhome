<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPagoNegocio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_pago_negocio';

    protected $fillable = [
        'nombre',
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

    public function pagosNegocio(): HasMany
    {
        return $this->hasMany(PagoNegocio::class, 'tipo_pago_negocio_id');
    }
}

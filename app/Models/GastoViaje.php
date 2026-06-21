<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GastoViaje extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'gastos_viaje';

    protected $fillable = [
        'viaje_id',
        'hogar_miembro_id',
        'categoria_gasto_viaje_id',
        'descripcion',
        'monto',
        'moneda_id',
        'fecha',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'fecha' => 'date',
        ];
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }

    public function hogarMiembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'hogar_miembro_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaGastoViaje::class, 'categoria_gasto_viaje_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }
}

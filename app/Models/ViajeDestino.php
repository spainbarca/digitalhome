<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ViajeDestino extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'viaje_destinos';

    protected $fillable = [
        'viaje_id',
        'nombre',
        'distrito_inei',
        'pais_iso2',
        'ciudad_id',
        'fecha_llegada',
        'fecha_salida',
        'orden',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_llegada' => 'date',
            'fecha_salida'  => 'date',
            'orden'         => 'integer',
        ];
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(UbigeoDistrito::class, 'distrito_inei', 'inei');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'pais_iso2', 'iso2');
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }
}

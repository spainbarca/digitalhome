<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'reservas';

    protected $fillable = [
        'viaje_id',
        'operador_viaje_id',
        'tipo_reserva_id',
        'tipo_transporte_id',
        'estado_reserva_id',
        'titulo',
        'codigo_reserva',
        'fecha_inicio',
        'fecha_fin',
        'monto',
        'moneda_id',
        'origen',
        'destino',
        'numero_servicio',
        'asiento',
        'num_noches',
        'tipo_habitacion',
        'direccion',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin'    => 'datetime',
            'monto'        => 'decimal:2',
            'num_noches'   => 'integer',
        ];
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }

    public function operadorViaje(): BelongsTo
    {
        return $this->belongsTo(OperadorViaje::class, 'operador_viaje_id');
    }

    public function tipoReserva(): BelongsTo
    {
        return $this->belongsTo(TipoReserva::class, 'tipo_reserva_id');
    }

    public function tipoTransporte(): BelongsTo
    {
        return $this->belongsTo(TipoTransporte::class, 'tipo_transporte_id');
    }

    public function estadoReserva(): BelongsTo
    {
        return $this->belongsTo(EstadoReserva::class, 'estado_reserva_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoViaje::class, 'reserva_id');
    }
}

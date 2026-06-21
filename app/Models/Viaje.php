<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Viaje extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'viajes';

    protected $fillable = [
        'hogar_id',
        'tipo_viaje_id',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'presupuesto',
        'moneda_id',
        'portada_path',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin'    => 'date',
            'presupuesto'  => 'decimal:2',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function tipoViaje(): BelongsTo
    {
        return $this->belongsTo(TipoViaje::class, 'tipo_viaje_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function destinos(): HasMany
    {
        return $this->hasMany(ViajeDestino::class, 'viaje_id');
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(ViajeParticipante::class, 'viaje_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'viaje_id');
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(GastoViaje::class, 'viaje_id');
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'viaje_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoViaje::class, 'viaje_id');
    }

    public function getPortadaUrlAttribute(): ?string
    {
        return $this->portada_path ? asset('storage/' . $this->portada_path) : null;
    }
}

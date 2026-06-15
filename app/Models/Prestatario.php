<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestatario extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'prestatarios';

    protected $fillable = [
        'hogar_id',
        'miembro_id',
        'moneda_id',
        'nombre',
        'es_miembro_hogar',
        'hogar_miembro_id',
        'telefono',
        'foto_path',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'es_miembro_hogar' => 'boolean',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'miembro_id');
    }

    public function hogarMiembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'hogar_miembro_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function movimientosPrestamo(): HasMany
    {
        return $this->hasMany(MovimientoPrestamo::class, 'prestatario_id');
    }

    public function getNombreResueltoAttribute(): string
    {
        if ($this->es_miembro_hogar && $this->hogarMiembro) {
            return $this->hogarMiembro->apodo ?? $this->hogarMiembro->user?->name ?? $this->nombre;
        }
        return $this->nombre;
    }

    public function getFotoResueltaAttribute(): ?string
    {
        if ($this->es_miembro_hogar && $this->hogarMiembro) {
            return $this->hogarMiembro->user?->persona?->foto_url;
        }
        return $this->foto_path ? asset('storage/' . $this->foto_path) : null;
    }

    public function getSaldoAttribute(): float
    {
        $movimientos = $this->relationLoaded('movimientosPrestamo')
            ? $this->movimientosPrestamo
            : $this->movimientosPrestamo()->get();

        return $movimientos->sum(fn ($m) => match ($m->tipo) {
            'cargo'     => (float) $m->monto,
            'abono',
            'descuento' => -(float) $m->monto,
            default     => 0.0,
        });
    }
}

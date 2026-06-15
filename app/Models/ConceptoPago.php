<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConceptoPago extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'conceptos_pago';

    protected $fillable = [
        'hogar_id',
        'categoria_concepto_id',
        'nombre',
        'descripcion',
        'precio_referencial',
        'imagen_path',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio_referencial' => 'decimal:2',
            'activo'             => 'boolean',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function categoriaConcepto(): BelongsTo
    {
        return $this->belongsTo(CategoriaConcepto::class, 'categoria_concepto_id');
    }

    public function movimientosPrestamo(): HasMany
    {
        return $this->hasMany(MovimientoPrestamo::class, 'concepto_pago_id');
    }

    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen_path
            ? asset('storage/' . $this->imagen_path)
            : null;
    }
}

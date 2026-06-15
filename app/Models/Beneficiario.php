<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiario extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'beneficiarios';

    protected $fillable = [
        'producto_financiero_id',
        'hogar_miembro_id',
        'nombre',
        'parentesco',
        'documento_identidad',
        'porcentaje',
    ];

    protected function casts(): array
    {
        return [
            'porcentaje' => 'decimal:2',
        ];
    }

    public function productoFinanciero(): BelongsTo
    {
        return $this->belongsTo(ProductoFinanciero::class, 'producto_financiero_id');
    }

    public function hogarMiembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'hogar_miembro_id');
    }

    public function getNombreResueltoAttribute(): string
    {
        return $this->hogarMiembro?->user?->name ?? $this->nombre;
    }
}

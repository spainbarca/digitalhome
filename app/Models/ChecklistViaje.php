<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistViaje extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'checklist_viaje';

    protected $fillable = [
        'viaje_id',
        'descripcion',
        'completado',
        'hogar_miembro_id',
        'fecha_limite',
        'orden',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'completado'   => 'boolean',
            'fecha_limite' => 'date',
            'orden'        => 'integer',
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
}

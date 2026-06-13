<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpleoBeneficio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'empleo_beneficios';

    protected $fillable = [
        'empleo_id',
        'tipo_beneficio_id',
        'detalle',
        'entidad',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function empleo(): BelongsTo
    {
        return $this->belongsTo(Empleo::class, 'empleo_id');
    }

    public function tipoBeneficio(): BelongsTo
    {
        return $this->belongsTo(TipoBeneficio::class, 'tipo_beneficio_id');
    }
}

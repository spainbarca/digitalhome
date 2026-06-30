<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtencionVeterinaria extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'atenciones_veterinarias';

    protected $fillable = [
        'mascota_id',
        'centro_veterinario_id',
        'fecha',
        'motivo',
        'diagnostico',
        'tratamiento',
        'costo',
        'moneda_id',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'costo' => 'decimal:2',
        ];
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function centroVeterinario(): BelongsTo
    {
        return $this->belongsTo(CentroVeterinario::class, 'centro_veterinario_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoMascota::class, 'atencion_veterinaria_id');
    }
}

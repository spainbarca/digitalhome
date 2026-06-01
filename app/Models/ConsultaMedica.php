<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultaMedica extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'consultas_medicas';

    protected $fillable = [
        'hogar_id',
        'hogar_miembro_id',
        'medico_id',
        'centro_medico_id',
        'especialidad_medica_id',
        'moneda_id',
        'fecha',
        'hora',
        'motivo',
        'diagnostico',
        'tratamiento_indicado',
        'costo',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha'   => 'date',
            'costo'   => 'decimal:2',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function hogarMiembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'hogar_miembro_id');
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function centroMedico(): BelongsTo
    {
        return $this->belongsTo(CentroMedico::class, 'centro_medico_id');
    }

    public function especialidadMedica(): BelongsTo
    {
        return $this->belongsTo(EspecialidadMedica::class, 'especialidad_medica_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function documentosMedicos(): HasMany
    {
        return $this->hasMany(DocumentoMedico::class, 'consulta_medica_id');
    }
}

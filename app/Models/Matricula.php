<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matricula extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'matriculas';

    protected $fillable = [
        'hogar_id',
        'hogar_miembro_id',
        'institucion_educativa_id',
        'nivel_educativo_id',
        'turno_educativo_id',
        'estado_matricula_id',
        'moneda_id',
        'año_lectivo',
        'grado_ciclo',
        'nombre_tutor',
        'telefono_tutor',
        'costo_mensual',
        'dia_vencimiento_pago',
        'fecha_inicio',
        'fecha_fin',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'costo_mensual'        => 'decimal:2',
            'dia_vencimiento_pago' => 'integer',
            'fecha_inicio'         => 'date',
            'fecha_fin'            => 'date',
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

    public function institucionEducativa(): BelongsTo
    {
        return $this->belongsTo(InstitucionEducativa::class, 'institucion_educativa_id');
    }

    public function nivelEducativo(): BelongsTo
    {
        return $this->belongsTo(NivelEducativo::class, 'nivel_educativo_id');
    }

    public function turnoEducativo(): BelongsTo
    {
        return $this->belongsTo(TurnoEducativo::class, 'turno_educativo_id');
    }

    public function estadoMatricula(): BelongsTo
    {
        return $this->belongsTo(EstadoMatricula::class, 'estado_matricula_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function pagosEducativos(): HasMany
    {
        return $this->hasMany(PagoEducativo::class, 'matricula_id');
    }

    public function documentosEducativos(): HasMany
    {
        return $this->hasMany(DocumentoEducativo::class, 'matricula_id');
    }
}

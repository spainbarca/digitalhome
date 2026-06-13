<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'empleos';

    protected $fillable = [
        'hogar_id',
        'hogar_miembro_id',
        'empleador_id',
        'modalidad_laboral_id',
        'estado_empleo_id',
        'cargo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'salario',
        'moneda_id',
        'es_actual',
        'logros',
        'fecha_vencimiento_contrato',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'               => 'date',
            'fecha_fin'                  => 'date',
            'fecha_vencimiento_contrato' => 'date',
            'salario'                    => 'decimal:2',
            'es_actual'                  => 'boolean',
            'activo'                     => 'boolean',
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

    public function empleador(): BelongsTo
    {
        return $this->belongsTo(Empleador::class, 'empleador_id');
    }

    public function modalidadLaboral(): BelongsTo
    {
        return $this->belongsTo(ModalidadLaboral::class, 'modalidad_laboral_id');
    }

    public function estadoEmpleo(): BelongsTo
    {
        return $this->belongsTo(EstadoEmpleo::class, 'estado_empleo_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function empleoBeneficios(): HasMany
    {
        return $this->hasMany(EmpleoBeneficio::class, 'empleo_id');
    }

    public function empleoReferencias(): HasMany
    {
        return $this->hasMany(EmpleoReferencia::class, 'empleo_id');
    }

    public function documentosLaborales(): HasMany
    {
        return $this->hasMany(DocumentoLaboral::class, 'empleo_id');
    }

    public function capacitaciones(): HasMany
    {
        return $this->hasMany(Capacitacion::class, 'empleo_id');
    }
}

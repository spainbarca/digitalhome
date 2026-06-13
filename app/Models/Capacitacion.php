<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capacitacion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'capacitaciones';

    protected $fillable = [
        'hogar_id',
        'hogar_miembro_id',
        'empleo_id',
        'institucion_educativa_id',
        'tipo_capacitacion_id',
        'nombre',
        'descripcion',
        'institucion_nombre',
        'fecha_inicio',
        'fecha_fin',
        'fecha_vencimiento',
        'file_path',
        'notas',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'      => 'date',
            'fecha_fin'         => 'date',
            'fecha_vencimiento' => 'date',
            'activo'            => 'boolean',
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

    public function empleo(): BelongsTo
    {
        return $this->belongsTo(Empleo::class, 'empleo_id');
    }

    public function institucionEducativa(): BelongsTo
    {
        return $this->belongsTo(InstitucionEducativa::class, 'institucion_educativa_id');
    }

    public function tipoCapacitacion(): BelongsTo
    {
        return $this->belongsTo(TipoCapacitacion::class, 'tipo_capacitacion_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstitucionEducativa extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'instituciones_educativas';

    protected $fillable = [
        'hogar_id',
        'empresa_id',
        'tipo_institucion_educativa_id',
        'nombre_referencial',
        'imagen_path',
        'banner_path',
        'logo_path',
        'notas',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoInstitucionEducativa(): BelongsTo
    {
        return $this->belongsTo(TipoInstitucionEducativa::class, 'tipo_institucion_educativa_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'institucion_educativa_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroMedico extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'centros_medicos';

    protected $fillable = [
        'hogar_id',
        'empresa_id',
        'tipo_centro_medico_id',
        'nombre',
        'direccion',
        'telefono',
        'sitio_web',
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

    public function tipoCentroMedico(): BelongsTo
    {
        return $this->belongsTo(TipoCentroMedico::class, 'tipo_centro_medico_id');
    }

    public function consultasMedicas(): HasMany
    {
        return $this->hasMany(ConsultaMedica::class, 'centro_medico_id');
    }
}

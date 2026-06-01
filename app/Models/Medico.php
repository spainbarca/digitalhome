<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medico extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'medicos';

    protected $fillable = [
        'hogar_id',
        'especialidad_medica_id',
        'nombres',
        'apellidos',
        'cmp',
        'telefono',
        'email',
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

    public function especialidadMedica(): BelongsTo
    {
        return $this->belongsTo(EspecialidadMedica::class, 'especialidad_medica_id');
    }

    public function consultasMedicas(): HasMany
    {
        return $this->hasMany(ConsultaMedica::class, 'medico_id');
    }
}

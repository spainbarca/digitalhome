<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EspecialidadMedica extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'especialidades_medicas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'imagen_path',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function medicos(): HasMany
    {
        return $this->hasMany(Medico::class, 'especialidad_medica_id');
    }

    public function consultasMedicas(): HasMany
    {
        return $this->hasMany(ConsultaMedica::class, 'especialidad_medica_id');
    }
}

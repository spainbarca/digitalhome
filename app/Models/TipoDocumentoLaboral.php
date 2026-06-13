<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumentoLaboral extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_documento_laboral';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'requiere_vencimiento',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'requiere_vencimiento' => 'boolean',
            'activo'               => 'boolean',
        ];
    }

    public function documentosLaborales(): HasMany
    {
        return $this->hasMany(DocumentoLaboral::class, 'tipo_documento_laboral_id');
    }
}

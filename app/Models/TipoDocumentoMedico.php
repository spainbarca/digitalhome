<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumentoMedico extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_documento_medico';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function documentosMedicos(): HasMany
    {
        return $this->hasMany(DocumentoMedico::class, 'tipo_documento_medico_id');
    }
}

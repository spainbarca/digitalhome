<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumentoServicio extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_documento_servicio';

    protected $fillable = [
        'nombre',
        'icono',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function documentosServicio(): HasMany
    {
        return $this->hasMany(DocumentoServicio::class, 'tipo_documento_id');
    }
}

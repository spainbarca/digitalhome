<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoDocumentoLegal extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'estado_documento_legal';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function documentosLegales(): HasMany
    {
        return $this->hasMany(DocumentoLegal::class, 'estado_documento_legal_id');
    }
}

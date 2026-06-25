<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumentoLegal extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_documento_legal';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'categoria',
        'requiere_vencimiento',
        'relevante_viaje',
        'relevante_negocio',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'requiere_vencimiento' => 'boolean',
            'relevante_viaje'    => 'boolean',
            'relevante_negocio'  => 'boolean',
            'activo'             => 'boolean',
        ];
    }

    public function documentosLegales(): HasMany
    {
        return $this->hasMany(DocumentoLegal::class, 'tipo_documento_legal_id');
    }
}

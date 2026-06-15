<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumentoFinanciero extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_documento_financiero';

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

    public function documentosFinancieros(): HasMany
    {
        return $this->hasMany(DocumentoFinanciero::class, 'tipo_documento_financiero_id');
    }
}

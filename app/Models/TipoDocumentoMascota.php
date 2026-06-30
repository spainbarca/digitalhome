<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumentoMascota extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_documento_mascota';

    protected $fillable = [
        'nombre',
        'icono',
    ];

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoMascota::class, 'tipo_documento_mascota_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HistorialVersion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'historial_versiones';

    protected $fillable = [
        'versionable_id',
        'versionable_type',
        'archivo_url',
        'extension',
        'tamano_bytes',
        'subido_por',
        'motivo_cambio',
        'numero_version',
    ];

    protected function casts(): array
    {
        return [
            'tamano_bytes'   => 'integer',
            'numero_version' => 'integer',
        ];
    }

    public function versionable(): MorphTo
    {
        return $this->morphTo('versionable');
    }

    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    public function tipoArchivo(): BelongsTo
    {
        return $this->belongsTo(TipoArchivo::class, 'extension', 'extension');
    }
}

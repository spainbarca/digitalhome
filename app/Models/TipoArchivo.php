<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoArchivo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_archivo';

    protected $fillable = [
        'nombre',
        'extension',
        'mime_type',
        'icono',
        'permitido',
        'tamano_max_bytes',
    ];

    protected function casts(): array
    {
        return [
            'permitido'        => 'boolean',
            'tamano_max_bytes' => 'integer',
        ];
    }

    public function documentosServicio(): HasMany
    {
        return $this->hasMany(DocumentoServicio::class, 'extension', 'extension');
    }

    public function historialVersiones(): HasMany
    {
        return $this->hasMany(HistorialVersion::class, 'extension', 'extension');
    }
}

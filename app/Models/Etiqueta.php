<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Etiqueta extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'etiquetas';

    protected $fillable = [
        'hogar_id',
        'nombre',
        'color',
    ];

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function documentosServicio(): MorphedByMany
    {
        return $this->morphedByMany(
            DocumentoServicio::class,
            'etiquetable',
            'etiquetables',
            'etiqueta_id',
            'etiquetable_id'
        );
    }
}

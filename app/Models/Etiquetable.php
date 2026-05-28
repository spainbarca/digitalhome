<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Etiquetable extends Model
{
    use HasFactory;

    protected $table = 'etiquetables';

    protected $fillable = [
        'etiqueta_id',
        'etiquetable_id',
        'etiquetable_type',
    ];

    public function etiqueta(): BelongsTo
    {
        return $this->belongsTo(Etiqueta::class, 'etiqueta_id');
    }

    public function etiquetable(): MorphTo
    {
        return $this->morphTo('etiquetable');
    }
}
